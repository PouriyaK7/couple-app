<?php

namespace App\Repositories;

use App\Models\Plan;
use Illuminate\Support\Str;

class PlanRepository
{
    /**
     * Store plan in database
     * @param string $title
     * @param string $goal
     * @param string $userID
     * @param $date
     * @return Plan
     */
    public static function store(string $title, string $goal, string $userID, $date): Plan
    {
        return Plan::query()->create([
            'id' => Str::uuid(),
            'title' => $title,
            'goal' => $goal,
            'user_id' => $userID,
            'date' => $date
        ]);
    }

    /**
     * Update a plan in database
     * @param Plan $plan
     * @param string $title
     * @param string $goal
     * @param $date
     * @return bool
     */
    public static function update(Plan &$plan, string $title, string $goal, $date): bool
    {
        $plan->title = $title;
        $plan->goal = $goal;
        $plan->date = $date;
        return $plan->save();
    }

    /**
     * Delete a plan from database
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return Plan::query()->where('id', $id)->delete();
    }
}
