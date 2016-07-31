<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\GuestList\CreateRequest;
use App\Http\Requests\GuestList\UpdateRequest;
use App\Karina\GuestList;
use App\Karina\User;
use App\Transformers\GuestListTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuestListsController extends ApiController
{
    /**
     * Store a newly created guest list in storage.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $guestList = new GuestList;
        $guestList->fill($request->all());
        $guestList->user()->associate(Auth::user());

        $guestList = DB::transaction(function () use ($guestList, $request) {
            $users = new Collection();
            foreach ($request->input('guest') as $data) {
                $user = User::where('email', $data['email'])->first();
                if (!$user) {
                    $user = new User;
                    $user->fill($data);
                    $user->save();
                }

                $users->add($user);
            }

            $guestList->save();
            $guestList->users()->sync($users->pluck('id')->toArray());

            return $guestList->fresh();
        });

        return $this->respondWith($guestList, new GuestListTransformer);
    }

    /**
     * Update the specified event in storage.
     *
     * @param UpdateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $guestList = DB::transaction(function () use ($request, $id) {
            $guestList = GuestList::findOrFail($id);
            $this->authorize('handle', $guestList);
            $guestList->fill($request->all());

            $users = new Collection();
            foreach ($request->input('guest') as $data) {
                $user = User::where('email', $data['email'])->first();
                if (!$user) {
                    $user = new User;
                    $user->fill($data);
                    $user->save();
                }

                $users->add($user);
            }

            $guestList->save();
            $guestList->users()->sync($users->pluck('id')->toArray());

            return $guestList->fresh();
        });

        return $this->respondWith($guestList, new GuestListTransformer);
    }

    /**
     * Remove the specified guest list from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $guestList = GuestList::findOrFail($id);
            $this->authorize('handle', $guestList);
            $guestList->delete();
        });

        return $this->respondWithArray([
            'success' => true,
            'message' => 'Successfully deleted guest list.',
        ]);
    }
}
