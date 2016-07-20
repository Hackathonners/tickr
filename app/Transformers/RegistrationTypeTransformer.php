<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Karina\RegistrationType;

class RegistrationTypeTransformer extends TransformerAbstract
{
    /**
     * Transform a registration type into a generic array.
     *
     * @param  \App\Karina\Event
     * @return array
     */
    public function transform(RegistrationType $registrationType)
    {
        return [
            'id' => $registrationType->id,
            'type' => $registrationType->type,
            'price' => $registrationType->price,
            'fine' => $registrationType->fine,
        ];
    }
}
