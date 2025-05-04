<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FutureExhibition;
use App\Http\Controllers\Controller;

class ManagCurrentController extends Controller
{
    public function index()
    {
        $exhibitions = FutureExhibition::orderBy('exhibition_date', 'desc')->paginate(10);
        return view('admin.manage_current_exhibition', compact('exhibitions'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'type' => 'required|in:future,current',
            'exhibition_date' => 'required|date',
            'mediums' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'sections' => 'required|json'
        ]);

        try {
            $validated['sections'] = json_decode($validated['sections'], true);
            $exhibition = FutureExhibition::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition created successfully',
                'exhibition' => $exhibition
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating exhibition: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate(['id' => 'required|exists:future_exhibitions,id']);
        $exhibition = FutureExhibition::find($request->id);
        $exhibition->sections = json_encode($exhibition->sections);
        return response()->json($exhibition);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:future_exhibitions,id',
            'title' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'type' => 'nullable|in:future,current',
            'exhibition_date' => 'required|date',
            'mediums' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'sections' => 'nullable|json'
        ]);

        try {
            $validated['sections'] = json_decode($validated['sections'], true);
            $exhibition = FutureExhibition::find($request->id);
            $exhibition->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition updated successfully',
                'exhibition' => $exhibition
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating exhibition: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|exists:future_exhibitions,id']);

        try {
            FutureExhibition::find($request->id)->delete();
            return response()->json([
                'success' => true,
                'message' => 'Exhibition deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting exhibition: ' . $e->getMessage()
            ], 500);
        }
    }
}
