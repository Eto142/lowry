<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FutureExhibition;
use App\Http\Controllers\Controller;

class ManageCurrentExhibitionController extends Controller
{
    public function index()
    {
        $exhibitions = FutureExhibition::orderBy('exhibition_date', 'desc')->paginate(10);
        return view('admin.exhibitions.index', compact('exhibitions'));
    }

    public function create()
    {
        return view('admin.exhibitions.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        try {
            $validated['sections'] = $this->processSections($request);
            FutureExhibition::create($validated);
            
            return redirect()->route('admin.exhibitions.index')
                ->with('success', 'Exhibition created successfully');
                
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating exhibition: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $exhibition = FutureExhibition::findOrFail($id);
        return view('admin.exhibitions.edit', compact('exhibition'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateRequest($request);
        
        try {
            $exhibition = FutureExhibition::findOrFail($id);
            $validated['sections'] = $this->processSections($request);
            $exhibition->update($validated);
            
            return redirect()->route('admin.exhibitions.index')
                ->with('success', 'Exhibition updated successfully');
                
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating exhibition: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            FutureExhibition::findOrFail($id)->delete();
            return redirect()->route('admin.exhibitions.index')
                ->with('success', 'Exhibition deleted successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting exhibition: ' . $e->getMessage());
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'type' => 'required|in:future,current',
            'exhibition_date' => 'required|date',
            'mediums' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
        ]);
    }

    private function processSections(Request $request)
    {
        $sections = [];
        foreach ($request->section_titles as $index => $title) {
            $sections[] = [
                'title' => $title,
                'description' => $request->section_descriptions[$index] ?? null
            ];
        }
        return json_encode($sections);
    }
}
