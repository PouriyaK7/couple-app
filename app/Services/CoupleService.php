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
     * @param string $partnerID
     * @param string $description
     * @param null $date
     * @param string|null $nickname
     * @return Model|null
     */
    public static function create(string $title, string $userID, string $partnerID, string $description, $date = null, string $nickname = null): Model|null
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

        # TODO create event for this
        # Add partners to couple
        self::addPartner($userID, $couple->id, $userID);
        self::addPartner($partnerID, $couple->id, $userID, $nickname);

        return $couple;
    }

    /**
     * Add partner to existing couple
     * @param string $partnerID
     * @param string $coupleID
     * @param string $userID
     * @param string|null $nickname
     * @return bool
     */
    public static function addPartner(string $partnerID, string $coupleID, string $userID, string $nickname = null): bool
    {
        # Check if user has access to add partner to couple
        if (!self::checkAccess($userID, $coupleID)) {
            return false;
        }
        # Create couple
        return (bool)CoupleUser::query()->create([
            'id' => Str::uuid()->toString(),
            'couple_id' => $coupleID,
            'user_id' => $partnerID,
            'nickname' => $nickname
        ]);
    }

    public static function removePartner(string $partnerID, string $coupleID, string $userID): bool
    {
        # Check if user has access to remove partner from couple
        if (!self::checkAccess($userID, $coupleID)) {
            return false;
        }
        # Remove partner
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

