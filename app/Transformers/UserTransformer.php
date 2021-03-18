<?php

declare(strict_types=1);

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 * @package App\User
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id'    => (int) $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }

    /**
     * @param User $user
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(User $user)
    {
        return $this->item($user->user, new UserTransformer());
    }
}
