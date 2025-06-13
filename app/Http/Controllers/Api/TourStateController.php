<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TourStateController extends Controller
{
    /**
     * Get all tour states for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tourStates = $user->tourStates;

        return response()->json([
            'completedSteps' => $this->formatCompletedSteps($tourStates),
            'completedTours' => $this->formatCompletedTours($tourStates),
        ]);
    }

    /**
     * Get a specific tour state by tour name.
     *
     * @param Request $request
     * @param string $tourName
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $tourName)
    {
        $user = $request->user();
        $tourState = $user->tourStates()->where('tour_name', $tourName)->first();

        if (!$tourState) {
            return response()->json([
                'completedSteps' => [],
                'isCompleted' => false,
            ]);
        }

        return response()->json([
            'completedSteps' => $tourState->completed_steps ?? [],
            'isCompleted' => $tourState->is_completed,
        ]);
    }

    /**
     * Create or update a tour state.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'tourName' => 'required|string',
            'completedSteps' => 'nullable|array',
            'isCompleted' => 'boolean',
        ]);

        $tourState = $user->tourStates()->updateOrCreate(
            ['tour_name' => $data['tourName']],
            [
                'completed_steps' => $data['completedSteps'] ?? [],
                'is_completed' => $data['isCompleted'] ?? false,
            ]
        );

        return response()->json($tourState);
    }

    /**
     * Mark a step as completed.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeStep(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'tourName' => 'required|string',
            'stepId' => 'required|string',
        ]);

        $tourState = $user->tourStates()->firstOrCreate(
            ['tour_name' => $data['tourName']],
            ['completed_steps' => []]
        );

        $completedSteps = $tourState->completed_steps ?? [];

        if (!in_array($data['stepId'], $completedSteps)) {
            $completedSteps[] = $data['stepId'];
            $tourState->update(['completed_steps' => $completedSteps]);
        }

        return response()->json($tourState);
    }

    /**
     * Mark a tour as completed.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeTour(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'tourName' => 'required|string',
        ]);

        $tourState = $user->tourStates()->updateOrCreate(
            ['tour_name' => $data['tourName']],
            ['is_completed' => true]
        );

        return response()->json($tourState);
    }

    /**
     * Reset a specific tour.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'tourName' => 'required|string',
        ]);

        $user->tourStates()->where('tour_name', $data['tourName'])->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Reset all tours.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetAll(Request $request)
    {
        $user = $request->user();
        $user->tourStates()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Format completed steps from tour states.
     *
     * @param \Illuminate\Database\Eloquent\Collection $tourStates
     * @return array
     */
    private function formatCompletedSteps($tourStates)
    {
        $completedSteps = [];

        foreach ($tourStates as $tourState) {
            $completedSteps[$tourState->tour_name] = $tourState->completed_steps ?? [];
        }

        return $completedSteps;
    }

    /**
     * Format completed tours from tour states.
     *
     * @param \Illuminate\Database\Eloquent\Collection $tourStates
     * @return array
     */
    private function formatCompletedTours($tourStates)
    {
        $completedTours = [];

        foreach ($tourStates as $tourState) {
            $completedTours[$tourState->tour_name] = $tourState->is_completed;
        }

        return $completedTours;
    }
}
