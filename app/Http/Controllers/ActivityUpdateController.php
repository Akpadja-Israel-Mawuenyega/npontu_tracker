<?php

namespace App\Http\Controllers;

use App\Models\ActivityUpdate;
use App\Http\Requests\StoreActivityUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * ============================================================================
 * ActivityUpdateController
 * ============================================================================
 *
 * Handles progress reporting for activities, including status transitions
 * and attribution of personnel.
 */
class ActivityUpdateController extends Controller
{
    /**
     * Store a status update for a specific activity.
     *
     * @param StoreActivityUpdateRequest $request
     * @param int $activityId
     * @return RedirectResponse
     */
    public function store(StoreActivityUpdateRequest $request, int $activityId) : RedirectResponse
    {
        // Use the validated data from the Form Request
        $data = $request->validated();

        $data['activity_id'] = $activityId;
        $data['user_id']     = Auth::id();

        ActivityUpdate::create($data);

        return back()->with('success', 'Update recorded successfully.');
    }
}
