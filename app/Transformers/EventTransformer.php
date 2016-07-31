<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Karina\Event;

class EventTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var  array
     */
    protected $availableIncludes = ['registration_types'];

    /**
     * List of resources to automatically include.
     *
     * @var  array
     */
    protected $defaultIncludes = ['registration_types'];

    /**
     * Transform an event into a generic array.
     *
     * @param Event $event
     * @return array
     */
    public function transform(Event $event)
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'place' => $event->place,
            'start_at' => $event->start_at->toDateTimeString(),
            'end_at' => $event->end_at->toDateTimeString(),
            'created_at' => $event->created_at->toDateTimeString(),
            'updated_at' => $event->updated_at->toDateTimeString(),
        ];
    }

    public function includeRegistrationTypes(Event $event)
    {
        return $this->collection($event->registrationTypes, new RegistrationTypeTransformer);
    }
}
