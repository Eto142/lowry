<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    public function index()
    {
        // Get all exhibitions with either user or admin relationship
        $exhibitions = Exhibition::with('admin')
            ->orderBy('date', 'asc')
            ->get();

        return view('home.homepage', [
            'exhibitions' => $exhibitions
        ]);
    }

    public function show(Exhibition $exhibition)
    {
        return view('exhibitions.show', [
            'exhibition' => $exhibition->load(['user', 'admin'])
        ]);
    }
}
