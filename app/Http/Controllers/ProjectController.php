<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // 2. Creation
        $project = Project::create([
            'creator_id' => auth()->id(), // Associate the project with the logged-in user
            'project_name' => $validated['project_name'],
            'description' => $validated['description'],
            // Status defaults to 'Proposed' in migration
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return response()->json([
            'message' => 'Project created successfully. Awaiting approval.',
            'project' => $project
        ], 201);
    }

    // ... other methods (index, show, update, destroy)
}
