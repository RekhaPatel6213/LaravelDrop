<?php
    /**
     * @OA\Get(path="/hub/promo-codes",
     *   tags={"PromoCodes"},
     *   summary="Promo Code Listing.",
     *   description="List all promo code data",
     *   @OA\Parameter(
     *         description="Search String - Enter keyword to search",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/PromoCodeSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/PromoCodeList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('promo-codes', 'PromoCodeController@list');

    /**
     * @OA\Get(path="/hub/promo-codes/{promoCodeId}",
     *   tags={"PromoCodes"},
     *   summary="Promo code details",
     *   description="Promo code details",
     *   @OA\Parameter(name="promoCodeId", in="path", description="Promo code id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/PromoCodeInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('promo-codes/{promoCodeId}', 'PromoCodeController@getPromoCode')->where('promoCodeId', '[0-9]+')->name('promo_code.get');


    /**
     * @OA\Post(path="/hub/promo-codes",
     *   tags={"PromoCodes"},
     *   summary="Add promo code",
     *   description="Add promo code",
     *   operationId="store",
     *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PromoCodeInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/PromoCodeInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('promo-codes', 'PromoCodeController@store')->name('promo_code.save');

    /**
     * @OA\post(path="/hub/promo-codes/{promoCodeId}",
     *   tags={"PromoCodes"},
     *   summary="Update promo code",
     *   description="Update promo code",
     *   @OA\Parameter(name="promoCodeId", in="path", description="Promo Code id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PromoCodeInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/PromoCodeInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('promo-codes/{promoCodeId}', 'PromoCodeController@store')->where('promoCodeId', '[0-9]+')->name('promo_code.update');


    /**
     * @OA\patch(path="/hub/promo-codes/{promoCodeId}",
     *   tags={"PromoCodes"},
     *   summary="Update promo code",
     *   description="Update promo code",
     *   @OA\Parameter(name="promoCodeId", in="path", description="Promo Code id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PromoCodePatchData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/PromoCodeInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('promo-codes/{promoCodeId}', 'PromoCodeController@update')->where('promoCodeId', '[0-9]+')->name('promo_code.patch');


    /**
     * @OA\Delete(path="/hub/promo-codes/{promoCodeId}",
     *   tags={"PromoCodes"},
     *   summary="Delete promo code",
     *   description="Delete promo code",
     *   @OA\Parameter(name="promoCodeId", in="path", description="Promo Code id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('promo-codes/{promoCodeId}', 'PromoCodeController@delete')->where('promoCodeId', '[0-9]+')->name('promo_code.delete');

