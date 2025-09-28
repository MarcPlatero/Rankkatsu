<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RankingController extends Controller
{
    public function index()
    {
        $rankings = Ranking::latest()->get();

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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'options'     => 'required|array|min:2',
            'options.*.name'  => 'required|string|max:255',
            'options.*.image' => 'nullable|image|max:2048',
        ]);

        // Guardar rànquing
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rankings', 'public');
        }
        $ranking = Ranking::create($validated);

        // Guardar opcions
        foreach ($request->options as $option) {
            $data = ['name' => $option['name']];
            if (isset($option['image'])) {
                $data['image'] = $option['image']->store('options', 'public');
            }
            $ranking->options()->create($data);
        }

        return redirect()->route('rankings.index')
            ->with('success', 'Ranking creat correctament!');
    }
}
