<?php

    /**
     * @OA\Get(path="/hub/dispensary",
     *   tags={"Dispensary"},
     *   summary="Get single dispensary",
     *   description="Get single dispensary by dispensary id",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('dispensary', 'Dispensary\DispensaryController@getDispensary');


    /**
     * @OA\post(path="/hub/dispensary",
     *   tags={"Dispensary"},
     *   summary="Update dispensary",
     *   description="Shop setting API, use fields which are required to update shop setting",
     *   @OA\RequestBody(required=true,
     *   description="Dispensary Data Update Request body",
     *   @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/HubDispensaryUpdateRequest"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/HubDispensaryUpdateResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('dispensary', 'Dispensary\DispensaryController@update');

    /**
     * @OA\Post(path="/hub/dispensary/change-password",
     *    tags={"Dispensary"},
     *    summary="Change Password Dispensary User",
     *    description="Change Dispensary User Password",
     *    requestBody={"$ref":"#/components/requestBodies/ChangePassword"},
     *    @OA\Response(response=200, description="password reset success"),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     *  )
     */
    Route::post('dispensary/change-password', 'Dispensary\DispensaryController@changePassword');
