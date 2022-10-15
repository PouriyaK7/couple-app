<?php

namespace App\Http\Controllers\User;

use App\Events\UpdateCoupleEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoupleRequest;
use App\Models\Couple;
use App\Models\CoupleUser;
use App\Repositories\CoupleRepository;
use Illuminate\Support\Facades\Auth;

class CoupleController extends Controller
{
    /**
     * Get all user couples
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        return CoupleUser::query()->where('user_id', Auth::id())->pluck('couple_id');
    }

    /**
     * Take user to create couple form
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('hello');
    }

    /**
     * Take user to edit couple form
     * @param string $id
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(string $id)
    {
        # Fetch couple from database and check if it exists
        $couple = Couple::query()->find($id);
        if (empty($couple)) {
            abort(404);
        }

        # Check if user has access to couple
        $this->authorize('access', $couple);

        return view('hello', compact('couple'));
    }

    /**
     * Store couple in db
     * @param CoupleRequest $request
     * @return \Illuminate\Database\Eloquent\Model|string
     */
    public function store(CoupleRequest $request)
    {
        # Get validated data
        $data = $request->validated();
        # Create couple and return error on failure
        $couple = CoupleRepository::store($data['title'], Auth::id(), $data['description'], $data['date']);
        if (empty($couple)) {
            return 'something went wrong please try again later';
        }
        # Trigger update couple event
        # TODO develop listeners for it
        event(new UpdateCoupleEvent(
            $couple,
            ['partners' => [Auth::id(), $data['partner_id']], 'nickname' => $data['nickname']]
        ));

        return $couple;
    }

    /**
     * Update existing couple
     * @param CoupleRequest $request
     * @param string $id
     * @return Couple|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CoupleRequest $request, string $id)
    {
        # Get validated data from request
        $data = $request->validated();

        # Fetch couple from database and check if exists
        $couple = Couple::query()->find($id);
        if (empty($couple)) {
            abort(404);
        }

        # Check if user has access to couple
        $this->authorize('access', $couple);

        # Update couple
        CoupleRepository::update($couple, $data['title'], $data['description'], $data['date']);

        return $couple;
    }

    /**
     * Delete couple from db
     * @param string $id
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(string $id)
    {
        # Fetch couple from db and check if exists
        $couple = Couple::query()->find($id);
        if (empty($couple)) {
            abort(404);
        }

        # Check if user has access to couple
        $this->authorize('access', $couple);

        # Delete couple and its partners from it
        CoupleRepository::delete($id);
        CoupleRepository::removePartners($id);
        return 'deleted successfully';
    }
}
