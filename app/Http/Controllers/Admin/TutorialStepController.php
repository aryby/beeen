<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\TutorialStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutorialStepController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tutorial $tutorial)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url|max:500',
            'step_order' => 'required|integer|min:1',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('tutorial-steps', $filename, 'public');
            $validated['image'] = $path;
        }

        $validated['tutorial_id'] = $tutorial->id;
        $step = TutorialStep::create($validated);

        return redirect()->route('admin.tutorials.show', $tutorial)
            ->with('success', 'Étape ajoutée avec succès !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tutorial $tutorial, TutorialStep $step)
    {
        return view('admin.tutorials.steps.edit', compact('tutorial', 'step'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tutorial $tutorial, TutorialStep $step)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url|max:500',
            'step_order' => 'required|integer|min:1',
        ]);

        // Gérer l'upload de la nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($step->image && Storage::disk('public')->exists($step->image)) {
                Storage::disk('public')->delete($step->image);
            }

            $image = $request->file('image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('tutorial-steps', $filename, 'public');
            $validated['image'] = $path;
        }

        $step->update($validated);

        return redirect()->route('admin.tutorials.show', $tutorial)
            ->with('success', 'Étape mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutorial $tutorial, TutorialStep $step)
    {
        // Supprimer l'image associée
        if ($step->image && Storage::disk('public')->exists($step->image)) {
            Storage::disk('public')->delete($step->image);
        }

        $step->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Reorder steps
     */
    public function reorder(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'steps' => 'required|array',
            'steps.*.id' => 'required|exists:tutorial_steps,id',
            'steps.*.step_order' => 'required|integer|min:1',
        ]);

        foreach ($request->steps as $stepData) {
            TutorialStep::where('id', $stepData['id'])
                ->where('tutorial_id', $tutorial->id)
                ->update(['step_order' => $stepData['step_order']]);
        }

        return response()->json(['success' => true]);
    }
}