<?php

declare(strict_types=1);

namespace App\Services;

use App\Organisation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class OrganisationService
 * @package App\Services
 */
class OrganisationService
{
    /**
     * @param array $attributes
     *
     * @return Organisation
     */
    public function createOrganisation(array $attributes): Organisation
    {
        $organisation = new Organisation($attributes);
        $organisation->save();
        return $organisation;
    }

    /**
     * @param string $filter
     *
     * @return EloquentCollection
     */
    public function getOrganisations(string $filter): EloquentCollection
    {
        $filter = ($filter == 'subbed') ? 1 : (($filter == 'trial') ? 0 : null);
        return Organisation::with('owner')
            ->when($filter !== null, function ($q) use ($filter) {
                return $q->where('subscribed', $filter);
            })->get();
    }
}
