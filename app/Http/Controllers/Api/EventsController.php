<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Exceptions\Event\CannotUpdateEventException;
use App\Http\Requests\Event\UpdateRequest;
use DB;
use Auth;
use App\Http\Requests\Event\CreateRequest;
use App\Karina\Event;
use App\Karina\RegistrationType;
use App\Transformers\EventTransformer;

class EventsController extends ApiController
{
    /**
     * Display a listing of the event.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $events = DB::transaction(function () use ($filter) {
            $user = Auth::user();

            $events = $user->events();

            // Apply filter to events if valid
            if (in_array($filter, ['past'], true)) {
                $events = $events->$filter();
            }

            return $events->paginate();
        });

        return $this->respondWith($events, new EventTransformer);
    }

    /**
     * Store a newly created event in storage.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
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

        return $this->respondWith($event, new EventTransformer);
    }

    /**
     * Display the specified event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = DB::transaction(function () use ($id) {
            $event = Event::findOrFail($id);
            $this->authorize('handle', $event);

            return $event;
        });

        return $this->respondWith($event, new EventTransformer);
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
        try {
            $event = DB::transaction(function () use ($id, $request) {
                $event = Event::findOrFail($id);
                $this->authorize('handle', $event);
                $event->fill($request->all());
                $event->save();
                $event->fresh();

                return $event;
            });

            return $this->respondWith($event, new EventTransformer());
        } catch (CannotUpdateEventException $e) {
            return $this->errorForbidden($e->getMessage());
        }
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $event = Event::findOrFail($id);
            $this->authorize('handle', $event);
            $event->delete();
        });

        return $this->respondWithArray([
            'success' => true,
            'message' => 'Successfully deleted event.',
        ]);
    }
}
