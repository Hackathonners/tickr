<?php

namespace App\Transformers;

use App\Karina\Guest;
use League\Fractal\TransformerAbstract;

class GuestTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var  array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include.
     *
     * @var  array
     */
    protected $defaultIncludes = [];

    /**
     * Transform an guest into a generic array.
     *
     * @param Guest $guest
     * @return array
     */
    public function transform(Guest $guest)
    {
        return [
            'name' => $guest->name,
            'notes' => $guest->notes,
        ];
    }
}
