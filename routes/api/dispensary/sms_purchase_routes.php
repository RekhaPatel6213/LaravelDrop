<?php
	
	/**
     * @OA\Get(
     *   tags={"Dispensary Pruchase SMS"},
     * 	 path="/admin/sms/{dispensaryId}",
     *   summary="SMS Pruchase History",
     *   description="SMS Pruchase History",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SmsHistory")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('sms/{dispensaryId}', 'Dispensary\SmsController@history')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Get(
     *      tags = {"Dispensary Pruchase SMS"},
     *      path = "/admin/sms_group",
     *      summary = "Get SMS Group List",
     *      description = "API for Getting SMS Group List",
     *      operationId="getSMSGroupList",
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SmsGroupList")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('sms_group', 'Dispensary\SmsController@getSMSGroups');

    /**
     * @OA\Get(
     *      tags = {"Dispensary Pruchase SMS"},
     *      path = "/admin/sms_price",
     *      summary = "Get SMS Prices",
     *      description = "API for Getting SMS Prices",
     *      operationId="getSMSPrices",
     *      @OA\Parameter(name="smsGroupName", in="query", description="SMS Group Name", required=true, example="3 Months Recurring", @OA\Schema(type="string")),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SmsPriceList")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('sms_price', 'Dispensary\SmsController@getSMSPrices');

    /**
     * @OA\Post(
     *   tags={"Dispensary Pruchase SMS"},
     *   path="/admin/sms",
     *   summary = "purchasing sms for dispensary",
     *   description = "API of purchasing sms for dispensary",
     *   requestBody={"$ref": "#/components/requestBodies/PruchaseSMS"},
     *   @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('sms', 'Dispensary\SmsController@purchaseSMS');

    /**
     * @OA\Post(
     *   tags={"Testing APIs"},
     *   path="/admin/sms-used",
     *   summary = "Using dispensary sms",
     *   description = "API of using dispensary sms",
     *   requestBody={"$ref": "#/components/requestBodies/UsedSMS"},
     *   @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('sms-used', 'Dispensary\SmsController@deductSMS');