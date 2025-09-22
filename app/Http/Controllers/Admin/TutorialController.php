<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use App\Models\TutorialStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $deviceType = $request->get('device_type');
        $status = $request->get('status');

        $tutorials = Tutorial::with(['steps'])
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('intro', 'like', "%{$search}%");
            })
            ->when($deviceType, function ($query, $deviceType) {
                return $query->where('device_type', $deviceType);
            })
            ->when($status, function ($query, $status) {
                if ($status === 'published') {
                    return $query->where('is_published', true);
                } elseif ($status === 'draft') {
                    return $query->where('is_published', false);
                }
            })
            ->ordered()
            ->paginate(20);

        $stats = [
            'total' => Tutorial::count(),
            'published' => Tutorial::published()->count(),
            'draft' => Tutorial::where('is_published', false)->count(),
            'total_steps' => TutorialStep::count(),
        ];

        $deviceTypes = Tutorial::getDeviceTypes();

        return view('admin.tutorials.index', compact(
            'tutorials', 
            'stats', 
            'deviceTypes', 
            'search', 
            'deviceType', 
            'status'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deviceTypes = Tutorial::getDeviceTypes();
        return view('admin.tutorials.create', compact('deviceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'device_type' => 'required|string|in:' . implode(',', array_keys(Tutorial::getDeviceTypes())),
            'intro' => 'required|string|max:1000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('tutorials', $filename, 'public');
            $validated['featured_image'] = $path;
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['sort_order'] = $validated['sort_order'] ?? Tutorial::max('sort_order') + 1;

        $tutorial = Tutorial::create($validated);

        return redirect()->route('admin.tutorials.show', $tutorial)
            ->with('success', 'Tutoriel créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tutorial $tutorial)
    {
        $tutorial->load(['steps' => function($query) {
            $query->ordered();
        }]);

        return view('admin.tutorials.show', compact('tutorial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tutorial $tutorial)
    {
        $deviceTypes = Tutorial::getDeviceTypes();
        return view('admin.tutorials.edit', compact('tutorial', 'deviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tutorial $tutorial)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'device_type' => 'required|string|in:' . implode(',', array_keys(Tutorial::getDeviceTypes())),
            'intro' => 'required|string|max:1000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Gérer l'upload de la nouvelle image
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image
            if ($tutorial->featured_image && Storage::disk('public')->exists($tutorial->featured_image)) {
                Storage::disk('public')->delete($tutorial->featured_image);
            }

            $image = $request->file('featured_image');
            $filename = Str::slug($validated['title']) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('tutorials', $filename, 'public');
            $validated['featured_image'] = $path;
        }

        $validated['is_published'] = $request->has('is_published');

        $tutorial->update($validated);

        return redirect()->route('admin.tutorials.show', $tutorial)
            ->with('success', 'Tutoriel mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutorial $tutorial)
    {
        // Supprimer l'image associée
        if ($tutorial->featured_image && Storage::disk('public')->exists($tutorial->featured_image)) {
            Storage::disk('public')->delete($tutorial->featured_image);
        }

        // Supprimer les images des étapes
        foreach ($tutorial->steps as $step) {
            if ($step->image && Storage::disk('public')->exists($step->image)) {
                Storage::disk('public')->delete($step->image);
            }
        }

        $tutorial->delete();

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutoriel supprimé avec succès !');
    }

    /**
     * Toggle publication status
     */
    public function toggleStatus(Tutorial $tutorial)
    {
        $tutorial->update(['is_published' => !$tutorial->is_published]);
        
        $status = $tutorial->is_published ? 'publié' : 'dépublié';
        return redirect()->back()
            ->with('success', "Tutoriel {$status} avec succès.");
    }

    /**
     * Duplicate a tutorial
     */
    public function duplicate(Tutorial $tutorial)
    {
        $newTutorial = $tutorial->replicate();
        $newTutorial->title = $tutorial->title . ' (Copie)';
        $newTutorial->is_published = false;
        $newTutorial->sort_order = Tutorial::max('sort_order') + 1;
        $newTutorial->save();

        // Dupliquer les étapes
        foreach ($tutorial->steps as $step) {
            $newStep = $step->replicate();
            $newStep->tutorial_id = $newTutorial->id;
            $newStep->save();
        }

        return redirect()->route('admin.tutorials.show', $newTutorial)
            ->with('success', 'Tutoriel dupliqué avec succès !');
    }

    /**
     * Reorder tutorials
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'tutorials' => 'required|array',
            'tutorials.*.id' => 'required|exists:tutorials,id',
            'tutorials.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->tutorials as $tutorialData) {
            Tutorial::where('id', $tutorialData['id'])
                ->update(['sort_order' => $tutorialData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
