<?php
    /**
     * @OA\Get(path="/hub/deals",
     *   tags={"Deals"},
     *   summary="deal Listing.",
     *   description="List all deal data",
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
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/DealSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/DealList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('deals', 'DealController@list')->name('deals.list');

    /**
     * @OA\Get(path="/hub/deals/{dealId}",
     *   tags={"Deals"},
     *   summary="Deal details",
     *   description="Deal details",
     *   @OA\Parameter(name="dealId", in="path", description="Deal id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DealsInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('deals/{dealId}', 'DealController@getDeal')->where('dealId', '[0-9]+')->name('deals.get');


    /**
     * @OA\Post(path="/hub/deals",
     *   tags={"Deals"},
     *   summary="Add deal",
     *   description="Add deal",
     *   requestBody={"$ref": "#/components/requestBodies/DealAddRequest"},
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DealsInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('deals', 'DealController@store')->name('deals.save');

    /**
     * @OA\post(path="/hub/deals/{dealId}",
     *   tags={"Deals"},
     *   summary="Update deal",
     *   description="Update deal",
     *   @OA\Parameter(name="dealId", in="path", description="deal id", required=true, example="1", @OA\Schema(type="integer")),
     *   requestBody={"$ref": "#/components/requestBodies/DealAddRequest"},
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DealsInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('deals/{dealId}', 'DealController@store')->where('dealId', '[0-9]+')->name('deals.update');


    /**
     * @OA\patch(path="/hub/deals/{dealId}",
     *   tags={"Deals"},
     *   summary="Update deal",
     *   description="Update deal",
     *   @OA\Parameter(name="dealId", in="path", description="deal id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/DealPatchData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DealsInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('deals/{dealId}', 'DealController@update')->where('dealId', '[0-9]+')->name('deals.patch');


    /**
     * @OA\Delete(path="/hub/deals/{dealId}",
     *   tags={"Deals"},
     *   summary="Delete deal",
     *   description="Delete deal",
     *   @OA\Parameter(name="dealId", in="path", description="deal id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('deals/{dealId}', 'DealController@delete')->where('dealId', '[0-9]+')->name('deals.delete');

    /**
     * @OA\Get(path="/hub/brand/{search}",
     *   tags={"Deals"},
     *   summary="Brand Listing.",
     *   description="Basic brand listing",
     *   @OA\Parameter(in="path", name="search", @OA\Schema(type="string")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/BrandInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('brand/{search}', 'DealController@brandList')->name('brands.list');

