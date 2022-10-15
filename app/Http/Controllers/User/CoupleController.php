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
    public function index()
    {
        return CoupleUser::query()->where('user_id', Auth::id())->pluck('couple_id');
    }

    public function create()
    {
        return view('hello');
    }

    public function edit(string $id)
    {
        $couple = Couple::query()->find($id);

        $this->authorize('access', $couple);

        return view('hello', compact('couple'));
    }

    /**
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

    public function update(CoupleRequest $request, string $id)
    {
        $data = $request->validated();

        $couple = Couple::query()->find($id);

        $this->authorize('access', $couple);

        CoupleRepository::update($couple, $data['title'], $data['description'], $data['date']);

        return $couple;
    }

    public function delete(string $id)
    {
        $couple = Couple::query()->find($id);
        if (empty($couple)) {
            abort(404);
        }

        $this->authorize('access', $couple);

        CoupleRepository::delete($id);
        CoupleRepository::removePartners($id);
        return 'deleted successfully';
    }
}
