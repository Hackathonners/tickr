<?php

namespace App\Transformers;

use App\Karina\RegistrationType;
use League\Fractal\TransformerAbstract;

class RegistrationTypeTransformer extends TransformerAbstract
{
    /**
     * Transform a registration type into a generic array.
     *
     * @param RegistrationType $registrationType
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
