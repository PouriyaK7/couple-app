<?php

namespace App\Repositories;

use App\Models\Couple;
use App\Models\CoupleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoupleRepository
{
    /**
     * Check user access query
     * @param string $coupleID
     * @param string $userID
     * @return int|bool
     */
    public static function checkAccess(string $coupleID, string $userID): int|bool
    {
        return Couple::query()->where('user_id', $userID)
            ->where('id', $coupleID)
            ->count();
    }

    /**
     * Remove all partners from a couple
     * @param string $coupleID
     * @return bool
     */
    public static function removePartners(string $coupleID): bool
    {
        return CoupleUser::query()->where('couple_id', $coupleID)->delete();
    }

    /**
     * Delete a couple from db
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        return Couple::query()->where('id', $id)->delete();
    }

    /**
     * Remove a partner from a couple
     * @param string $id
     * @param string $partnerID
     * @return bool
     */
    public static function removePartner(string $id, string $partnerID): bool
    {
        return CoupleUser::query()->where('couple_id', $id)
            ->where('user_id', $partnerID)
            ->delete();
    }

    /**
     * Add a partner to couple
     * @param string $id
     * @param string $partnerID
     * @param string|null $nickname
     * @return Model
     */
    public static function addPartner(string $id, string $partnerID, string $nickname = null): ?Model
    {
        return CoupleUser::query()->create([
            'id' => Str::uuid()->toString(),
            'couple_id' => $id,
            'user_id' => $partnerID,
            'nickname' => $nickname
        ]);
    }

    /**
     * Update couple infos in db
     * @param Model $couple
     * @param string $title
     * @param string|null $description
     * @param null $date
     * @return Model|null
     */
    public static function update(Model &$couple, string $title, string $description = null, $date = null): ?Model
    {
        $couple->title = $title;
        $couple->description = $description;
        $couple->date = $date;
        $couple->save();
        return $couple;
    }

    /**
     * Store couple in db
     * @param string $title
     * @param string $userID
     * @param string|null $description
     * @param null $date
     * @return Model|null
     */
    public static function store(string $title, string $userID, string $description = null, $date = null): ?Model
    {
        return Couple::query()->create([
            'id' => Str::uuid()->toString(),
            'title' => $title,
            'user_id' => $userID,
            'description' => $description,
            'date' => $date
        ]);
    }
}
