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
        // Recollir dades i usuari
        $search = $request->input('search');
        $sort = $request->input('sort', 'popular');
        $user = Auth::user();

        $query = Ranking::with('options')
            ->with('user');

        // Aplicar filtres de privacitat
        $query->when(!$user || !$user->is_admin, function ($q) use ($user) {
            if ($user) {
                $q->where(function ($sub) use ($user) {
                    $sub->where('is_approved', true)
                        ->orWhere('user_id', $user->id);
                });
            } else {
                $q->where('is_approved', true);
            }
        });

        // Aplicar filtre de cerca
        $query->when($search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('options', function ($optQuery) use ($search) {
                        $optQuery->where('name', 'like', "%{$search}%");
                    });
            });
        });

        if ($sort === 'recent') {
            $query->latest();
        } 
        elseif ($sort === 'trending') {
            // Ordenar per vots de les últimes 24h
            $query->withCount(['votes as recent_votes_count' => function ($query) {
                $query->where('ranking_votes.created_at', '>=', now()->subDay());
            }])
            ->orderBy('recent_votes_count', 'desc')
            ->orderBy('created_at', 'desc');
        } 
        else {
            // Per defecte està en 'popular'
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        }

        // Paginació
        $rankings = $query->paginate(20)->withQueryString();

        // Processar favorits
        $favoriteIds = $user
            ? $user->favoriteRankings()->pluck('rankings.id')->toArray()
            : [];

        $rankings->getCollection()->transform(function ($ranking) use ($favoriteIds) {
            $ranking->is_favorite = in_array($ranking->id, $favoriteIds);
            return $ranking;
        });

        return inertia('Rankings/Index', [
            'rankings' => $rankings,
            'filters' => [
                'search' => $search,
                'sort' => $sort 
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Rankings/Create');
    }

    public function store(Request $request)
    {
        // Validació amb missatges personalitzats
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:4096',
            'image_is_suspicious' => 'nullable|boolean',
            'options' => 'required|array|min:2',
            'options.*.name' => 'required|string|max:255',
            'options.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:4096',
            'options.*.video_url' => ['nullable', 'url', 'regex:/^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/'],
            'options.*.is_suspicious' => 'nullable|boolean',
        ], [
            'title.required' => 'Has d’introduir un títol per al rànquing.',
            'title.max' => 'El títol no pot tenir més de 255 caràcters.',
            'image.image' => 'El fitxer ha de ser una imatge vàlida.',
            'image.mimes' => 'Formats admesos: JPG, JPEG, PNG, WEBP, AVIF, GIF.',
            'image.max' => 'La imatge no pot superar els 4MB.',
            'options.required' => 'Has d’afegir opcions al rànquing.',
            'options.min' => 'Has d’afegir almenys 2 opcions.',
            'options.*.name.required' => 'Cada opció ha de tenir un nom.',
            'options.*.video_url.regex' => 'La URL ha de ser un enllaç vàlid de YouTube.',
        ]);

        // Determinar si la imatge principal és sospitosa
        $mainSuspicious = $request->boolean('image_is_suspicious', false);

        // Crear el rànquing inicialment
        $ranking = Ranking::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image' => null,
            'image_is_suspicious' => $mainSuspicious,
            'is_approved' => true,
            'user_id' => auth()->id(),
        ]);

        // Imatge principal
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rankings', 'public');
            $ranking->update(['image' => $path]);
        }

        // Variable per saber si alguna opció és sospitosa
        $hasSuspiciousOption = false;

        // Opcions del rànquing
        foreach ($validated['options'] as $i => $opt) {
            $isSuspicious = $request->boolean("options.$i.is_suspicious", false);
            if ($isSuspicious) {
                $hasSuspiciousOption = true;
            }

            // Afegim video_url a la creació de l'opció
            $option = $ranking->options()->create([
                'name' => $opt['name'],
                'image' => null,
                'video_url' => $opt['video_url'] ?? null,
                'is_suspicious' => $isSuspicious,
                'is_approved' => !$isSuspicious,
            ]);

            if ($request->hasFile("options.$i.image")) {
                $optPath = $request->file("options.$i.image")->store('options', 'public');
                $option->update(['image' => $optPath]);
            }
        }

        // Si la imatge principal o qualsevol opció és sospitosa, marcar com no aprovat
        if ($mainSuspicious || $hasSuspiciousOption) {
            $ranking->update(['is_approved' => false]);
        }

        return redirect()->route('rankings.index')->with('success', 'Rànquing creat correctament!');
    }

    public function home(Request $request)
    {
        $user = Auth::user();

        $favoriteIds = $user
            ? $user->favoriteRankings()->pluck('rankings.id')->toArray()
            : [];

        // Tendència
        $trendingRankings = Ranking::with('user')
            ->withCount(['votes as recent_votes_count' => function ($query) {
                $query->where('ranking_votes.created_at', '>=', now()->subDay());
            }])
            ->where('is_approved', true)
            ->orderBy('recent_votes_count', 'desc')
            ->orderBy('created_at', 'desc') 
            ->take(20)
            ->get()
            ->map(function ($ranking) use ($favoriteIds) {
                $ranking->is_favorite = in_array($ranking->id, $favoriteIds);
                return $ranking;
            });

        // Més populars
        $topRankings = Ranking::with('user')
            ->withCount('likes')
            ->where('is_approved', true)
            ->orderBy('likes_count', 'desc')
            ->take(15)
            ->get()
            ->map(function ($ranking) use ($favoriteIds) {
                $ranking->is_favorite = in_array($ranking->id, $favoriteIds);
                return $ranking;
            });

        // Novetats
        $latestRankings = Ranking::with('user')
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($ranking) use ($favoriteIds) {
                $ranking->is_favorite = in_array($ranking->id, $favoriteIds);
                return $ranking;
            });

        return Inertia::render('Home', [
            'trendingRankings' => $trendingRankings,
            'topRankings' => $topRankings,
            'latestRankings' => $latestRankings,
        ]);
    }

    public function show(Request $request, Ranking $ranking)
    {
        $user = Auth::user();

        // Bloqueig si el rànquing no està aprovat i no és ni autor ni admin
        if (!$ranking->is_approved && (!$user || ($user->id !== $ranking->user_id && !$user->can('moderate')))) {
            abort(403, 'Aquest rànquing encara no ha estat aprovat.');
        }

        // Carregar opcions (si no és admin ni autor, només les aprovades)
        $ranking->load([
            'options' => function ($q) use ($user, $ranking) {
                if (!$user || (!$user->can('moderate') && $user->id !== $ranking->user_id)) {
                    $q->where('is_approved', true);
                }
                $q->withCount('votes');
            },
            'user',
        ]);

        // Estat de favorit
        $ranking->is_favorite = $user ? $user->favoriteRankings->contains($ranking->id) : false;
        $ranking->likes_count = $ranking->likes()->count();
        $ranking->user_has_liked = $user ? $ranking->likes()->where('user_id', $user->id)->exists() : false;

        // Vot de l'usuari per al rànquing (si està logejat)
        $userVote = null;
        if ($user) {
            $userVote = RankingVote::where('user_id', $user->id)
                ->whereHas('option', fn($q) => $q->where('ranking_id', $ranking->id))
                ->first();
        }

        // Ordenació dels comentaris
        $sort = $request->get('sort', 'likes');

        $commentsQuery = Comment::with(['user' => function($query) {
            $query->select('id', 'name', 'profile_photo_path', 'is_premium');
        }])
        ->where('ranking_id', $ranking->id)
        ->withCount([
            'votes as likes_count' => fn($q) => $q->where('is_like', 1),
            'votes as dislikes_count' => fn($q) => $q->where('is_like', 0),
        ]);

        switch ($sort) {
            case 'oldest':
                $commentsQuery->orderBy('created_at', 'asc');
                break;
            case 'likes':
                $commentsQuery->orderBy('likes_count', 'desc');
                break;
            default:
                $commentsQuery->orderBy('created_at', 'desc');
                break;
        }

        $comments = $commentsQuery->get();

        // Si usuari logejat, afegim estat de vot a cada comentari
        if ($user && $comments->isNotEmpty()) {
            $userVotes = CommentVote::where('user_id', $user->id)
                ->whereIn('comment_id', $comments->pluck('id'))
                ->get()
                ->keyBy('comment_id');

            $comments = $comments->map(function ($c) use ($userVotes) {
                $c->user_vote = isset($userVotes[$c->id])
                    ? (bool) $userVotes[$c->id]->is_like
                    : null;
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
        $user = Auth::user();

        // Permetre eliminar si és el creador o si és administrador
        if ($ranking->user_id !== $user->id && !$user->is_admin) {
            abort(403, 'No tens permís per eliminar aquest rànquing.');
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
}