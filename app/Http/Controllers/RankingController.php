<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingVote;
use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        $rankings = Ranking::with('options')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('options', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        // Gestionem favorits
        $favoriteIds = $user
            ? $user->favoriteRankings()->pluck('rankings.id')->toArray()
            : [];

        foreach ($rankings as $ranking) {
            $ranking->is_favorite = in_array($ranking->id, $favoriteIds);
        }

        return inertia('Rankings/Index', [
            'rankings' => $rankings,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Rankings/Create');
    }

    public function store(Request $request)
    {
        // Validació amb missatges personalitzats i més formats d'imatge
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            // acceptem molts formats i 4MB màxim
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:4096',
            // mínim 2 opcions
            'options' => 'required|array|min:2',
            'options.*.name' => 'required|string|max:255',
            // si envies imatges per a cada opció (opcional)
            'options.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:4096',
        ], [
            'title.required' => 'Has d’introduir un títol per al rànquing.',
            'title.max' => 'El títol no pot tenir més de 255 caràcters.',
            'image.image' => 'El fitxer ha de ser una imatge vàlida.',
            'image.mimes' => 'Formats admesos: JPG, JPEG, PNG, WEBP, AVIF, GIF.',
            'image.max' => 'La imatge no pot superar els 4MB.',
            'options.required' => 'Has d’afegir opcions al rànquing.',
            'options.min' => 'Has d’afegir almenys 2 opcions.',
            'options.*.name.required' => 'Cada opció ha de tenir un nom.',
        ]);

        // Comprova autenticació (si la ruta store està protegida per auth potser no cal)
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Has d\'iniciar sessió per crear un rànquing.');
        }

        // Guardar imatge del rànquing (si n'hi ha)
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rankings', 'public');
        }

        // Crear rànquing vinculat a l'usuari
        $ranking = Ranking::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'user_id' => auth()->id(),
        ]);

        // Guardar opcions: els arxius d'opció venen a $request->file("options.$i.image")
        foreach ($validated['options'] as $index => $opt) {
            $optionData = [
                'name' => $opt['name'],
            ];

            if ($request->hasFile("options.$index.image")) {
                $optionData['image'] = $request->file("options.$index.image")->store('options', 'public');
            }

            $ranking->options()->create($optionData);
        }

        // Redirigeix al show o index (segons preferència)
        return redirect()->route('rankings.index')->with('success', 'Rànquing creat correctament!');
    }

    public function home()
    {
        $rankings = Ranking::latest()->take(10)->get();
        return Inertia::render('Home', [
            'rankings' => $rankings,
        ]);
    }

    public function show(Request $request, Ranking $ranking)
    {
        // Carreguem opcions amb el recompte de vots
        $ranking->load(['options' => function ($q) {
            $q->withCount('votes');
        }]);

        // Afegim estat de favorit
        $user = Auth::user();
        $ranking->is_favorite = $user ? $user->favoriteRankings->contains($ranking->id) : false;

        // Vot de l'usuari per al rànquing (si està logejat)
        $userVote = null;
        if (Auth::check()) {
            $userVote = RankingVote::where('user_id', Auth::id())
                ->whereHas('option', fn($q) => $q->where('ranking_id', $ranking->id))
                ->first();
        }

        $sort = $request->get('sort', 'likes');

        $commentsQuery = Comment::with('user')
            ->where('ranking_id', $ranking->id)
            ->withCount([
                'votes as likes_count' => function ($q) {
                    $q->where('is_like', 1);
                },
                'votes as dislikes_count' => function ($q) {
                    $q->where('is_like', 0);
                }
            ]);

        // Ordenació segons el filtre
        switch ($sort) {
            case 'oldest':
                $commentsQuery->orderBy('created_at', 'asc');
                break;

            case 'likes':
                $commentsQuery->orderBy('likes_count', 'desc');
                break;

            default: // recent
                $commentsQuery->orderBy('created_at', 'desc');
                break;
        }

        $comments = $commentsQuery->get();

        // Si usuari logejat, afegim estat user_vote a cada comentari (true/false/null)
        if (Auth::check() && $comments->isNotEmpty()) {
            $userVotes = CommentVote::where('user_id', Auth::id())
                ->whereIn('comment_id', $comments->pluck('id')->toArray())
                ->get()
                ->keyBy('comment_id');

            $comments = $comments->map(function ($c) use ($userVotes) {
                $c->user_vote = isset($userVotes[$c->id]) ? (bool) $userVotes[$c->id]->is_like : null;
                return $c;
            });
        }

        $votedOptionId = $userVote?->ranking_option_id;

        return Inertia::render('Rankings/Show', [
            'ranking' => $ranking,
            'comments' => $comments,
            'sort' => $sort,
            'userVote' => $userVote,
            'votedOptionId' => $votedOptionId,
        ]);
    }

    public function unvote(Ranking $ranking)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Has d\'iniciar sessió per retirar el vot.');
        }

        RankingVote::where('user_id', Auth::id())
            ->whereHas('option', fn($q) => $q->where('ranking_id', $ranking->id))
            ->delete();

        return back()->with('success', 'Has retirat el teu vot.');
    }

    public function destroy(Ranking $ranking)
    {
        // Comprovar que és el creador
        if ($ranking->user_id !== Auth::id()) {
            abort(403);
        }

        $ranking->delete();

        return redirect()->route('rankings.index')
            ->with('success', 'El rànquing s’ha eliminat correctament!');
    }

    // Llista de rankings de l'usuari
    public function yours(Request $request)
    {
        $rankings = $request->user()->rankings()->latest()->get();

        return Inertia::render('Profile/Tabs/YourRankings', [
            'rankings' => $rankings,
        ]);
    }

    // Llista de favorits
    public function favorites(Request $request)
    {
        $favorites = $request->user()->favoriteRankings()->with('user')->get();

        return Inertia::render('Profile/Tabs/FavoriteRankings', [
            'favorites' => $favorites,
        ]);
    }

    // Afegir o treure de favorits
    public function toggleFavorite(Request $request, Ranking $ranking)
    {
        $user = $request->user();

        if ($user->favoriteRankings()->where('ranking_id', $ranking->id)->exists()) {
            $user->favoriteRankings()->detach($ranking->id);
            return back()->with('success', 'Ranking eliminat dels favorits.');
        } else {
            $user->favoriteRankings()->attach($ranking->id);
            return back()->with('success', 'Ranking afegit als favorits!');
        }
    }
}
