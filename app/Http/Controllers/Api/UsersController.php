<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Karina\User;
use App\Transformers\UserTransformer;

class UsersController extends ApiController
{
    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::transaction(function () use ($id) {
            if (filter_var($id, FILTER_VALIDATE_EMAIL) !== false) {
                $user = User::where(['email' => $id])->firstOrFail();
            } else {
                $user = User::findOrFail($id);
            }

            return $user;
        });

        return $this->respondWith($user, new UserTransformer);
    }
}
