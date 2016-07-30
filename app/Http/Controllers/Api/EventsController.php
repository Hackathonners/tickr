<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Event\CannotUpdateEventException;
use App\Http\Requests\Event\UpdateEventRequest;
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
use Symfony\Component\HttpFoundation\Response;

class EventsController extends Controller
{
    /**
     * Display a listing of the event.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = DB::transaction(function () {
            $user = Auth::user();

            return $user->events()->paginate();
        });

        return Fractal::collection($events, new EventTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($events))->toJson();
    }

    /**
     * Store a newly created event in storage.
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

        return Fractal::item($event, new EventTransformer)->toJson();
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \App\Http\Requests\Event\UpdateEventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, $id)
    {
        try {
            $event = DB::transaction(function () use ($id, $request) {
                $event = Event::findOrFail($id);
                $this->authorize('handle', $event);
                $event->fill($request->all());
                $event->save();

                return $event;
            });

            return Fractal::item($event, new EventTransformer)->toJson();
        } catch (CannotUpdateEventException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
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

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted event.',
        ], Response::HTTP_OK);
    }
}
