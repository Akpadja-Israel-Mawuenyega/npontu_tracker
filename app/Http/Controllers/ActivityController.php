<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * ============================================================================
 * ActivityController
 * ============================================================================
 *
 * Implements operations tracking management, daily overview structures,
 * and historical data filtering[cite: 15, 18, 19].
 * ============================================================================
 */
class ActivityController extends Controller
{
    /**
     * Display a comprehensive matrix of daily activities and their historical progress.
     * Fulfills Requirement #4: Handover visibility management.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var string $targetDate Parse date string or fall back to system today */
        $targetDate = $request->input('date', Carbon::today()->toDateString());

        $activities = Activity::with(['creator', 'updates.user'])
            ->whereDate('activity_date', $targetDate)
            ->get();

        return view('activities.index', compact('activities', 'targetDate'));
    }

    /**
     * Display the layout wizard interface to register a new support task.
     *
     * @return View
     */
    public function create(): View
    {
        return view('activities.create');
    }

    /**
     * Commit a fresh operational task signature to the tracking storage.
     * Fulfills Requirement #1: Dynamic item intake tracking.
     *
     * @param StoreActivityRequest $request The strictly validated tracking payload.
     * @return RedirectResponse
     */
    public function store(StoreActivityRequest $request): RedirectResponse
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->validated();

        /** @var int|null $userId */
        $userId = Auth::id();
        $attributes['created_by'] = $userId;

        Activity::create($attributes);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Operational support activity logged successfully.');
    }

    /**
     * Provide specialized reporting utilities to query activities over custom durations.
     * Fulfills Requirement #5: Strategic metrics extraction.
     *
     * @param Request $request
     * @return View
     */
    public function report(Request $request): View
    {
        $startDate = $request->input('from');
        $endDate = $request->input('to');

        $query = Activity::with(['creator', 'updates.user']);

        if ($startDate && $endDate) {
            $query->whereBetween('activity_date', [$startDate, $endDate]);
        }

        $activities = $query->orderBy('activity_date', 'desc')->get();

        return view('activities.report', compact('activities', 'startDate', 'endDate'));
    }
}
