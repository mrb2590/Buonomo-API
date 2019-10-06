<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\RequestProcessor;
use App\Http\Resources\Activity as ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified', 'permission:access-admin-dashboard']);
    }

    /**
     * Fetch all activity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Admin\Activity
     */
    public function index(Request $request)
    {
        $this->authorize('read', Activity::class);

        $processor = new RequestProcessor($request, Activity::class);

        return ActivityResource::collection($processor->index());
    }

    /**
     * Fetch one activity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \App\Http\Resources\Admin\Activity
     */
    public function show(Request $request, Activity $activity)
    {
        $this->authorize('read', $activity);

        $processor = new RequestProcessor($request);

        return new ActivityResource($processor->show($activity));
    }
}
