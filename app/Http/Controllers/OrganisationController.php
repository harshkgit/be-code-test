<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Organisation;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Transformers\OrganisationTransformer;
use League\Fractal;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function store(OrganisationService $service): JsonResponse
    {
        /** @var Organisation $organisation */
        $organisation = $service->createOrganisation($this->request->all());

        return $this
            ->transformItem('organisation', $organisation, ['user'])
            ->respond();
    }

    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function listAll(OrganisationService $service): JsonResponse
    {
        $filter = $this->request->query('filter', 'all');

        /** @var EloquentCollection $organisations */
        $organisations = $service->getOrganisations($filter);

        return $this
            ->transformCollection('organization', $organisations, ['user'])
            ->respond();
    }
}
