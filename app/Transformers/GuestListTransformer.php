<?php

namespace App\Transformers;

use App\Karina\GuestList;
use League\Fractal\TransformerAbstract;

class GuestListTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var  array
     */
    protected $availableIncludes = ['guests'];

    /**
     * List of resources to automatically include.
     *
     * @var  array
     */
    protected $defaultIncludes = ['guests'];

    /**
     * Transform a guest list into a generic array.
     *
     * @param GuestList $guestList
     * @return array
     */
    public function transform(GuestList $guestList)
    {
        return [
            'id' => $guestList->id,
            'name' => $guestList->name,
            'description' => $guestList->description,
        ];
    }

    public function includeGuests(GuestList $guestList)
    {
        return $this->collection($guestList->guests, new GuestTransformer);
    }
}
