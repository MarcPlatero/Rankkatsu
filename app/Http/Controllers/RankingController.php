<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingVote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RankingController extends Controller
{
    public function index()
    {
        $rankings = Ranking::with('options')->latest()->get();
        return Inertia::render('Rankings/Index', [
            'rankings' => $rankings,
        ]);
    }


    public function create()
    {
        return Inertia::render('Rankings/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array|min:2',
            'options.*.name' => 'required|string|max:255',
            'options.*.image' => 'nullable|image|max:2048',
        ]);

        // Guardar rànquing
        $rankingData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $rankingData['image'] = $request->file('image')->store('rankings', 'public');
        }

        $ranking = Ranking::create($rankingData);

        // Guardar opcions
        foreach ($request->options as $option) {
            $optionData = [
                'name' => $option['name'],
            ];

            if (isset($option['image']) && $option['image'] instanceof \Illuminate\Http\UploadedFile) {
                $optionData['image'] = $option['image']->store('options', 'public');
            }

            $ranking->options()->create($optionData);
        }

        return redirect()->route('rankings.index')
            ->with('success', 'Ranking creat correctament!');
    }

    public function home()
    {
        $rankings = Ranking::latest()->take(10)->get();
        return Inertia::render('Home', [
            'rankings' => $rankings,
        ]);
    }

    public function show(Ranking $ranking)
    {
        $ranking->load('options');

        return Inertia::render('Rankings/Show', [
            'ranking' => $ranking,
        ]);
    }
}
