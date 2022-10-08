<?php

namespace App\Services;

use App\Models\Couple;
use App\Models\CoupleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoupleService
{
    /**
     * Store new couple in db
     * @param string $title
     * @param string $userID
     * @param string $description
     * @param null $date
     * @return Model|null
     */
    public static function create(string $title, string $userID, string $description, $date = null): Model|null
    {
        # Store couple in db and return null on failure
        $couple = Couple::query()->create([
            'id' => Str::uuid()->toString(),
            'title' => $title,
            'user_id' => $userID,
            'description' => $description,
            'date' => $date
        ]);
        if (empty($couple)) {
            return null;
        }

        return $couple;
    }

    /**
     * Update couple infos
     * @param Model $couple
     * @param string $title
     * @param string $description
     * @param null $date
     * @return Model|null
     */
    public static function update(Model &$couple, string $title, string $description, $date = null): ?Model
    {
        $couple->title = $title;
        $couple->description = $description;
        $couple->date = $date;
        $couple->save();
        return $couple;
    }

    /**
     * Add partner to existing couple
     * @param string $partnerID
     * @param string $coupleID
     * @param string|null $nickname
     * @return bool
     */
    public static function addPartner(string $partnerID, string $coupleID, string $nickname = null): bool
    {
        return (bool)CoupleUser::query()->create([
            'id' => Str::uuid()->toString(),
            'couple_id' => $coupleID,
            'user_id' => $partnerID,
            'nickname' => $nickname
        ]);
    }

    /**
     * Remove partner from a couple
     * @param string $partnerID
     * @param string $coupleID
     * @return bool
     */
    public static function removePartner(string $partnerID, string $coupleID): bool
    {
        return CoupleUser::query()->where('couple_id', $coupleID)
            ->where('user_id', $partnerID)
            ->delete();
    }

    /**
     * Check if user has access to couple
     * @param string $userID
     * @param string $coupleID
     * @return bool
     */
    public static function checkAccess(string $userID, string $coupleID): bool
    {
        return Couple::query()->where('user_id', $userID)
            ->where('id', $coupleID)
            ->count();
    }
}

