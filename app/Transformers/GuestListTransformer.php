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
    protected $availableIncludes = ['users'];

    /**
     * List of resources to automatically include.
     *
     * @var  array
     */
    protected $defaultIncludes = ['users'];

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

    public function includeUsers(GuestList $guestList)
    {
        return $this->collection($guestList->users, new UserTransformer);
    }
}
