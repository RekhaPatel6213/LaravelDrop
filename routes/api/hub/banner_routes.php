<?php

/**
 * @OA\Get(
 *   tags={"Banner"},
 *   path="/hub/banner",
 *   summary="Hub Banner Listing.",
 *   description="List all hub banner data",
 *   @OA\Parameter(
 *         description="Search By Name - Enter keyword to search by id, name",
 *         in="query",
 *         name="search",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   @OA\Parameter(ref="#/components/parameters/sort"),
 *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/HubBannerSortsOn")),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubBannerList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 *
 */
Route::get('banner', 'BannerController@list')->name('banner.list');

/**
 * @OA\POST(
 *      tags = {"Banner"},
 *      path = "/hub/banner",
 *      summary = "Add Hub Banner",
 *      description = "API for creating new hub banner",
 *      operationId="createBanner",
 *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/HubBannerInputData"))),
 *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/HubBannerData")),
 *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *      @OA\Response(response=403, description="Forbidden"),
 *      @OA\Response(response=404, description="Invalid Client Code"),
 *      @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('banner', 'BannerController@save')->name('banner.input');

/**
 * @OA\Get(path="/hub/banner/{bannerId}",
 *   tags={"Banner"},
 *   summary="Get single banner",
 *   description="Get single banner by id",
 *   @OA\Parameter(name="bannerId", in="path", description="banner id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/HubBannerData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('banner/{bannerId}', 'BannerController@getBanner')->where('bannerId', '[0-9]+')->name('banner.input');

/**
 * @OA\post(path="/hub/banner/{bannerId}",
 *   tags={"Banner"},
 *   summary="Update a banner",
 *   description="Update a banner",
 *   @OA\Parameter(name="bannerId", in="path", description="Banner id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/HubBannerInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/HubBannerData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('banner/{bannerId}', 'BannerController@update')->where('bannerId', '[0-9]+')->name('banner.input');

/**
 * @OA\patch(path="/hub/banner/{bannerId}",
 *   tags={"Banner"},
 *   summary="Update banner status",
 *   description="Update banner status",
 *   operationId="updatePatchBanner",
 *   @OA\Parameter(name="bannerId", in="path", description="Banner id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/HubBannerPatchData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('banner/{bannerId}', 'BannerController@update')->where('bannerId', '[0-9]+')->name('banner.status');

/**
 * @OA\Delete(path="/hub/banner/{bannerId}",
 *   tags={"Banner"},
 *   summary="Delete single banner",
 *   description="Delete single banner by id",
 *   @OA\Parameter(name="bannerId", in="path", description="Banner id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('banner/{bannerId}', 'BannerController@delete')->where('bannerId', '[0-9]+');

/**
 * @OA\Get(
 *   tags={"Banner"},
 *   path="/hub/banner/redirect-detail",
 *   summary="Hub Banner Get Redirect Detail By Type.",
 *   description="List all banner redirect detail by type",
 *     @OA\Parameter(name="redirect_type", in="query", description="Redirect Type",  required=true, @OA\Schema(type="string", enum={"Categories","Deals","Brands","Products"})),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/RedirectDetail")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('banner/redirect-detail', 'BannerController@getRedirectDetail')->name('banner.input');

