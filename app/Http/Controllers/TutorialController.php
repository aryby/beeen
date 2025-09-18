<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutorial;
use App\Models\TutorialStep;

class TutorialController extends Controller
{
    /**
     * Liste des tutoriels
     */
    public function index(Request $request)
    {
        $deviceFilter = $request->get('device');
        $search = $request->get('search');

        $tutorials = Tutorial::published()
            ->when($deviceFilter, function ($query, $device) {
                return $query->byDevice($device);
            })
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('intro', 'like', "%{$search}%");
            })
            ->ordered()
            ->with(['steps' => function ($query) {
                $query->ordered()->limit(1);
            }])
            ->get();

        $deviceTypes = Tutorial::getDeviceTypes();
        $tutorialsByDevice = $tutorials->groupBy('device_type');

        return view('tutorials.index', compact('tutorials', 'deviceTypes', 'tutorialsByDevice', 'deviceFilter', 'search'));
    }

    /**
     * Afficher un tutoriel
     */
    public function show(Tutorial $tutorial)
    {
        if (!$tutorial->is_published) {
            abort(404);
        }

        $tutorial->load(['steps' => function ($query) {
            $query->ordered();
        }]);

        $relatedTutorials = Tutorial::published()
            ->where('device_type', $tutorial->device_type)
            ->where('id', '!=', $tutorial->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('tutorials.show', compact('tutorial', 'relatedTutorials'));
    }

    /**
     * Afficher une étape spécifique d'un tutoriel
     */
    public function step(Tutorial $tutorial, TutorialStep $step)
    {
        if (!$tutorial->is_published || $step->tutorial_id !== $tutorial->id) {
            abort(404);
        }

        $tutorial->load(['steps' => function ($query) {
            $query->ordered();
        }]);

        $currentStepIndex = $tutorial->steps->search(function ($item) use ($step) {
            return $item->id === $step->id;
        });

        $previousStep = $currentStepIndex > 0 ? $tutorial->steps[$currentStepIndex - 1] : null;
        $nextStep = $currentStepIndex < $tutorial->steps->count() - 1 ? $tutorial->steps[$currentStepIndex + 1] : null;

        return view('tutorials.step', compact('tutorial', 'step', 'previousStep', 'nextStep', 'currentStepIndex'));
    }
}