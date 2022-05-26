<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hub\PromoCodeRequest;
use App\Services\Hub\PromoCodeService;
use App\Transformers\Hub\PromoCodeTransformer;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    protected $service;
    protected $transformer;

    public function __construct(
        PromoCodeService $service,
        PromoCodeTransformer $transformer
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

    public function getPromoCode(PromoCodeRequest $request)
    {
        try {
            $promoCodeId = $request->route('promoCodeId');
            $promoCode = $this->service->find($promoCodeId);

            return $this->item($promoCode, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function store(PromoCodeRequest $request)
    {
        try {
            $promoCodeId = $request->route('promoCodeId');
            $requestData = $request->all();
            $promoCode = $this->service->store($requestData, $promoCodeId);

            return $this->item($promoCode, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function update(PromoCodeRequest $request)
    {
        try {
            $promoCodeId = $request->route('promoCodeId');
            $promoCode = $this->service->update($request->all(), $promoCodeId);

            return $this->item($promoCode, $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function delete(PromoCodeRequest $request)
    {
        try {
            $promoCodeId = $request->route('promoCodeId');
            $this->service->delete($promoCodeId);
            return $this->returnJsonResponse(['message' => __('message.promo_code_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
}
