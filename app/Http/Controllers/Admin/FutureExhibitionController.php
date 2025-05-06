<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\FutureExhibition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FutureExhibitionController extends Controller
{
    public function index()
    {
        $exhibitions = FutureExhibition::orderBy('exhibition_date')->get();
        return view('admin.future-exhibitions.index', compact('exhibitions'));
    }

    public function create()
    {
        return view('admin.future-exhibitions.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $validated['sections'] = $this->processSectionsInput($request->input('sections'));

        if ($request->hasFile('picture')) {
            $validated['picture_url'] = $request->file('picture')->store('exhibitions', 'public');
        }

        FutureExhibition::create($validated);

        return redirect()->route('admin.future-exhibitions.index')
            ->with('message', 'Exhibition created successfully');
    }

    public function edit(FutureExhibition $exhibition)
    {
        // Convert sections to pretty JSON for editing
        $exhibition->sections = $this->formatSectionsForEditing($exhibition->sections);
        return view('admin.future-exhibitions.edit', compact('exhibition'));
    }

    public function update(Request $request, FutureExhibition $exhibition)
    {
        $validated = $this->validateRequest($request);

        $validated['sections'] = $this->processSectionsInput($request->input('sections'));

        if ($request->hasFile('picture')) {
            if ($exhibition->picture_url) {
                Storage::disk('public')->delete($exhibition->picture_url);
            }
            $validated['picture_url'] = $request->file('picture')->store('exhibitions', 'public');
        }

        $exhibition->update($validated);

        return redirect()->route('admin.future-exhibitions.index')
            ->with('message', 'Exhibition updated successfully');
    }

    public function destroy(FutureExhibition $exhibition)
    {
        if ($exhibition->picture_url) {
            Storage::disk('public')->delete($exhibition->picture_url);
        }

        $exhibition->delete();

        return redirect()->route('admin.future-exhibitions.index')
            ->with('message', 'Exhibition deleted successfully');
    }

    /**
     * Validate the request data
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mediums' => 'required|string',
            'objective' => 'required|string',
            'sections' => 'nullable', // Changed from 'json' to be more flexible
            'budget' => 'required|numeric',
            'exhibition_date' => 'required|date',
            'type' => 'required|in:future,current',
            'is_featured' => 'nullable|boolean',
            'picture' => 'nullable|image|max:2048',
        ]);
    }

    /**
     * Process sections input (accepts both JSON and plain text)
     */
    protected function processSectionsInput($input)
    {
        if (empty($input)) {
            return null;
        }

        // Check if input is already JSON
        $decoded = json_decode($input);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Treat as plain text with one section per line
        $lines = array_filter(
            preg_split('/\r\n|\r|\n/', $input),
            function ($line) {
                return trim($line) !== '';
            }
        );

        return array_map('trim', $lines);
    }

    /**
     * Format sections for editing (convert to pretty JSON)
     */
    protected function formatSectionsForEditing($sections)
    {
        if (empty($sections)) {
            return '';
        }

        return json_encode($sections, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
