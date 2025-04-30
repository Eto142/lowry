<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FutureExhibition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CurrentExhibitionController extends Controller
{
    public function index()
    {
        $exhibitions = FutureExhibition::where('type', 'current')
            ->orderBy('exhibition_date')
            ->get();

        return view('admin.exhibitions.current.index', compact('exhibitions'));
    }

    public function create()
    {
        return view('admin.exhibitions.current.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mediums' => 'required|string',
            'objective' => 'required|string',
            'sections' => 'nullable|json',
            'budget' => 'required|numeric',
            'exhibition_date' => 'required|date',
            'is_featured' => 'boolean',
            'picture' => 'nullable|image|max:2048',
        ]);

        $validated['type'] = 'current';

        if ($request->hasFile('picture')) {
            $validated['picture_url'] = $request->file('picture')->store('exhibitions/current', 'public');
        }

        FutureExhibition::create($validated);

        return redirect()->route('admin.current-exhibitions.index')
            ->with('message', 'Current exhibition created successfully');
    }

    public function edit(FutureExhibition $exhibition)
    {
        return view('admin.exhibitions.current.edit', compact('exhibition'));
    }

    public function update(Request $request, FutureExhibition $exhibition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mediums' => 'required|string',
            'objective' => 'required|string',
            'sections' => 'nullable|json',
            'budget' => 'required|numeric',
            'exhibition_date' => 'required|date',
            'is_featured' => 'boolean',
            'picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            if ($exhibition->picture_url) {
                Storage::disk('public')->delete($exhibition->picture_url);
            }
            $validated['picture_url'] = $request->file('picture')->store('exhibitions/current', 'public');
        }

        $exhibition->update($validated);

        return redirect()->route('admin.current-exhibitions.index')
            ->with('message', 'Current exhibition updated successfully');
    }

    public function destroy(FutureExhibition $exhibition)
    {
        if ($exhibition->picture_url) {
            Storage::disk('public')->delete($exhibition->picture_url);
        }

        $exhibition->delete();

        return redirect()->route('admin.current-exhibitions.index')
            ->with('message', 'Current exhibition deleted successfully');
    }
}
