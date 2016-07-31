<?php

namespace App\Transformers;

use App\Karina\Registration;
use League\Fractal\TransformerAbstract;
use Vinkla\Hashids\Facades\Hashids;

class RegistrationTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var  array
     */
    protected $availableIncludes = ['registration_type'];

    /**
     * List of resources to automatically include.
     *
     * @var  array
     */
    protected $defaultIncludes = ['registration_type'];

    /**
     * Transform a registration into a generic array.
     *
     * @param Registration $registration
     * @return array
     */
    public function transform(Registration $registration)
    {
        return [
            'id' => Hashids::encode($registration->id),
            'event_id' => $registration->event_id,
            'user_id' => $registration->user_id,
            'fined' => (bool) $registration->fined,
            'activated' => (bool) $registration->activated,
        ];
    }

    public function includeRegistrationType(Registration $registration)
    {
        return $this->item($registration->registrationType, new RegistrationTypeTransformer);
    }
}
