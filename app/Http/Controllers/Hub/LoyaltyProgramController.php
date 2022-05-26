<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hub\LoyaltyProgramRequest;
use App\Services\Admin\Dispensary\LoyaltyProgramService;
use App\Transformers\Admin\Dispensary\LoyaltyProgramTransformer;
use Illuminate\Http\Request;

class LoyaltyProgramController extends Controller
{
    protected $service;
    protected $transformer;

    public function __construct(
        LoyaltyProgramService $service,
        LoyaltyProgramTransformer $transformer
    )
    {
        $this->service = $service;
        $this->transformer = $transformer;
    }

    public function list(Request $request)
    {
        try {
            $data = $this->service->list($request);
            return $this->paginateCollection($data, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getDefaults()
    {
        try {
            $data = $this->service->getDefaults();
            return $this->returnJsonResponse($data);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updateDefaults(LoyaltyProgramRequest $request)
    {
        try {
            $requestData = $request->all();
            $this->service->updateDefaults($requestData);
            return $this->getDefaults();
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getProgram(LoyaltyProgramRequest $request)
    {
        try {
            $programId = $request->route('programId');
            $promoCode = $this->service->find($programId);

            return $this->item($promoCode, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
    //TODO
    public function store(LoyaltyProgramRequest $request)
    {
        try {
            $programId = $request->route('programId');
            $requestData = $request->all();
            $program = $this->service->store($requestData, $programId);
            if (!$program['success']) {
                return response()->json(['message' => $program['message']]);
            }

            return $this->item($program['data'], $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function update(LoyaltyProgramRequest $request)
    {
        try {
            $programId = $request->route('programId');
            $requestData = $request->all();
            $program = $this->service->update($requestData, $programId);


            return $this->item($program, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function delete(LoyaltyProgramRequest $request)
    {
        try {
            $programId = $request->route('programId');
            $this->service->delete($programId);
            return $this->returnJsonResponse(['message' => __('message.program_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
