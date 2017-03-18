<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use App\Support\Str;
use Illuminate\Http\Request;
use App\Transformers\GuestTransformer;

class GuestsController extends ApiController
{
    /**
     * Display a listing of the guest.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->replace([
            'search' => Str::searchable($request->get('search', '')),
        ]);

        $this->validate($request, [
            'search' => 'sometimes|min:3',
        ]);

        $searchName = $request->get('search');
        $guests = DB::transaction(function () use ($searchName) {
            $user = Auth::user();

            $guests = $user->guests()->filterByName($searchName);

            return $guests->paginate();
        });

        return $this->respondWith($guests, new GuestTransformer);
    }
}
