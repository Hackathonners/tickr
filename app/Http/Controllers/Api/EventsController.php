<?php

namespace App\Http\Controllers\Api;

use App\Karina\User;
use DB;
use Auth;
use Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateEventRequest;
use App\Karina\Event;
use App\Karina\RegistrationType;
use App\Transformers\EventTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { Auth::login(User::first());
        $events = DB::transaction(function () {
            $user = Auth::user();
            return $user->events()->paginate();
        });

        return Fractal::collection($events, new EventTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($events))->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateEventRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEventRequest $request)
    {
        $event = new Event;
        $event->fill($request->all());
        $event->user()->associate(Auth::user());

        $registrationTypes = [];
        foreach ($request->input('registration') as $data) {
            $registrationType = new RegistrationType;
            $registrationType->fill($data);
            $registrationTypes[] = $registrationType;
        }

        $event = DB::transaction(function () use ($event, $registrationTypes) {
            $event->save();
            $event->registrationTypes()->saveMany($registrationTypes);

            return $event->fresh();
        });

        return Fractal::item($event, new EventTransformer)->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
