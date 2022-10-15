<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use App\Repositories\PlanRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanningController extends Controller
{
    /**
     * Get recent user plans from database
     * @return Plan[]
     */
    public function index()
    {
        return Plan::query()->where('user_id', Auth::id())
            ->orderByDesc('date')
            ->get();
    }

    /**
     * Show an individual plan of user
     * @param string $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $id)
    {
        # Fetch plan from db and check if exists
        $plan = Plan::query()->find($id);
        if (empty($plan)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $plan);

        # Fetch items from db
        $items = $plan->planItems;

        return compact('plan', 'items');
    }

    /**
     * Update plan in database
     * @param PlanRequest $request
     * @param string $id
     * @return Plan
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PlanRequest $request, string $id)
    {
        # Get validated data from request
        $data = $request->validated();

        # Fetch plan from db and check if it exists
        $plan = Plan::query()->find($id);
        if (empty($plan)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $plan);

        # Update plan in db
        PlanRepository::update($plan, $data['title'], $data['goal'], $data['date']);

        return $plan;
    }

    /**
     * Store plan in database
     * @param PlanRequest $request
     * @return Plan
     */
    public function store(PlanRequest $request)
    {
        # Get validated data from request
        $data = $request->validated();

        # Store plan in db
        return PlanRepository::store($data['title'], $data['goal'], Auth::id(), $data['date']);
    }
}
