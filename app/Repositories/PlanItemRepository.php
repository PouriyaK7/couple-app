<?php

namespace App\Repositories;

use App\Models\PlanItem;
use Illuminate\Support\Str;

class PlanItemRepository
{
    /**
     * Store plan item in database
     * @param string $title
     * @param string $planID
     * @param string $userID
     * @param string|null $description
     * @param null $dueDate
     * @return PlanItem
     */
    public static function store(string $title, string $planID, string $userID, string $description = null, $dueDate = null): PlanItem
    {
        return PlanItem::query()->create([
            'id' => Str::uuid(),
            'title' => $title,
            'plan_id' => $planID,
            'user_id' => $userID,
            'description' => $description,
            'due_date' => $dueDate
        ]);
    }

    /**
     * Update plan item in database
     * @param PlanItem $planItem
     * @param string $title
     * @param string|null $description
     * @param null $dueDate
     * @return bool
     */
    public static function update(PlanItem &$planItem, string $title, string $description = null, $dueDate = null): bool
    {
        $planItem->title = $title;
        $planItem->description = $description;
        $planItem->due_date = $dueDate;
        return $planItem->save();
    }

    /**
     * Change plan of item
     * @param string $id
     * @param string $planID
     * @return bool
     */
    public static function changePlan(string $id, string $planID): bool
    {
        return PlanItem::query()->where('id', $id)
            ->update([
                'plan_id' => $planID
            ]);
    }

    /**
     * Delete plan item from database
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return PlanItem::query()->where('id', $id)->delete();
    }

    /**
     * Toggle done status of plan item
     * @param PlanItem $item
     * @return bool
     */
    public static function toggleDone(PlanItem &$item): bool
    {
        if (isset($item->done_at)) {
            $item->done_at = null;
        } else {
            $item->done_at = now();
        }
        return $item->save();
    }
}
