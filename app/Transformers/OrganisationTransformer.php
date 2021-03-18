<?php

declare(strict_types=1);

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Organisation;

/**
 * Class OrganisationTransformer
 * @package App\Transformers
 */
class OrganisationTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user'
    ];

    /**
     * @param Organisation $organisation
     *
     * @return array
     */
    public function transform(Organisation $organisation): array
    {
        return [
            'id'        => (int) $organisation->id,
            'name'      => $organisation->name,
            'trial_end' => $organisation->trial_end,
            'subscribed' => (int) $organisation->subscribed
        ];
    }

    /**
     * @param Organisation $organisation
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Organisation $organisation)
    {
        return $this->item($organisation->owner, new UserTransformer);
    }
}
