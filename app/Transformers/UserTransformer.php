<?php

namespace App\Transformers;

use App\Karina\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
     * Transform an user into a generic array.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
