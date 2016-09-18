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
    protected $availableIncludes = ['registration_type, user'];

    /**
     * List of resources to automatically include.
     *
     * @var  array
     */
    protected $defaultIncludes = ['registration_type', 'user'];

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
            'fined' => (bool) $registration->fined,
            'activated' => (bool) $registration->activated,
            'notes' => $registration->notes,
            'created_at' => $registration->created_at->toDateTimeString(),
        ];
    }

    public function includeRegistrationType(Registration $registration)
    {
        return $this->item($registration->registrationType, new RegistrationTypeTransformer);
    }

    public function includeUser(Registration $registration)
    {
        return $this->item($registration->user, new UserTransformer);
    }
}
