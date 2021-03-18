<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

use App\Organisation;
use App\Services\OrganisationService;
use App\Http\Requests\StoreOrgnization;
use App\Mail\OrganizationCreated;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @param OrganisationService $service
     * @param StoreOrgnization $request
     *
     * @return JsonResponse
     */
    public function store(StoreOrgnization $request, OrganisationService $service): JsonResponse
    {
        $this->request->merge([
            'owner_user_id' => \Auth::id(),
            'trial_end'     => Carbon::now()->addDays(30),
            'subscribed'    => 0
        ]);

        try {
            /** @var Organisation $organisation */
            $organisation = $service->createOrganisation($this->request->all());

            /** send email to user **/
            \Mail::to(\Auth::user())
                ->send(new OrganizationCreated($organisation));

            return $this
                ->transformItem('organisation', $organisation, ['user'])
                ->respond();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while processing. ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * @param OrganisationService $service
     *
     * @return JsonResponse
     */
    public function listAll(OrganisationService $service): JsonResponse
    {
        $filter = $this->request->query('filter', 'all');
        try {
            /** @var EloquentCollection $organisations */
            $organisations = $service->getOrganisations($filter);
            return $this
                ->transformCollection('organization', $organisations, ['user'])
                ->respond();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error while processing. ' . $e->getMessage()
            ], 422);
        }
    }
}
