<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanItemRequest;
use App\Models\Plan;
use App\Models\PlanItem;
use App\Repositories\PlanItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanItemController extends Controller
{
    /**
     * Show plan items from a plan
     * @param string $planID
     * @return PlanItem[]
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $planID)
    {
        # Fetch plan from db and check if exists
        $plan = Plan::query()->find($planID);
        if (empty($plan)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $plan);

        return $plan->planItems;
    }

    /**
     * Store new plan item in db
     * @param PlanItemRequest $request
     * @return PlanItem
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PlanItemRequest $request)
    {
        # Get validated data from request
        $data = $request->validated();

        # Fetch plan from db and check if exists
        $plan = Plan::query()->find($data['plan_id']);
        if (empty($plan)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $plan);

        return PlanItemRepository::store($data['title'], $data['plan_id'], Auth::id(), $data['description'], $data['due_date']);
    }

    /**
     * Update existing plan item in db
     * @param PlanItemRequest $request
     * @param string $id
     * @return PlanItem
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PlanItemRequest $request, string $id)
    {
        # Get validated data from request
        $data = $request->validated();

        # Fetch plan from db and check if it exists
        $plan = Plan::query()->find($data['plan_id']);
        if (empty($plan)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $plan);

        # Fetch plan item from db and check if it exists
        $item = PlanItem::query()->find($id);
        if (empty($item)) {
            abort(404);
        }

        # Update plan item from db
        PlanItemRepository::update($item, $data['title'], $data['description'], $data['due_date']);

        return $item;
    }

    /**
     * Delete plan item from repository
     * @param string $id
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(string $id)
    {
        # Fetch plan item from db and check if it exists
        $item = PlanItem::query()->find($id);
        if (empty($item)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $item->plan);

        PlanItemRepository::delete($id);
    }

    /**
     * Change plan of plan item
     * @param string $id
     * @param string $planID
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changePlan(string $id, string $planID)
    {
        # Fetch plan from db and check if it exists
        $plan = Plan::query()->find($planID);
        if (empty($plan)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $plan);

        # Change plan item's plan
        PlanItemRepository::changePlan($id, $planID);
    }

    /**
     * Toggle done status of plan item
     * @param string $id
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggleDone(string $id)
    {
        # Fetch plan item from db and check if it exists
        $item = PlanItem::query()->find($id);
        if (empty($item)) {
            abort(404);
        }

        # Check if user has access to plan
        $this->authorize('access', $item->plan);

        # Toggle done status
        PlanItemRepository::toggleDone($item);
    }
}
