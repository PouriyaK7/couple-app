<?php

namespace App\Services;

use App\Models\Couple;
use App\Models\CoupleUser;
use App\Repositories\CoupleRepository;
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
        return CoupleRepository::store($title, $userID, $description, $date);
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
        return CoupleRepository::update($couple, $title, $description, $date);
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
        return (bool)CoupleRepository::addPartner($coupleID, $partnerID, $nickname);
    }

    /**
     * Remove partner from a couple
     * @param string $partnerID
     * @param string $coupleID
     * @return bool
     */
    public static function removePartner(string $partnerID, string $coupleID): bool
    {
        return CoupleRepository::removePartner($coupleID, $partnerID);
    }

    /**
     * Check if user has access to couple
     * @param string $userID
     * @param string $coupleID
     * @return bool
     */
    public static function checkAccess(string $userID, string $coupleID): bool
    {
        return CoupleRepository::checkAccess($coupleID, $userID);
    }

    /**
     * Delete couple and its users
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        # Remove all users from a couple
        CoupleRepository::removePartners($id);

        return CoupleRepository::delete($id);
    }
}

