<?php
/**
 * @OA\Get(path="/hub/smscampaign",
 *   tags={"SMSCampaigns"},
 *   summary="SMSCampaign Listing.",
 *   description="List all smscampaign data",
 *   @OA\Parameter(
 *         description="Search By Name - Enter keyword to search by id, message",
 *         in="query",
 *         name="search",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   @OA\Parameter(ref="#/components/parameters/sort"),
 *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/SMSCampaignSortsOn")),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/SMSCampaignList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 *
 */
Route::get('smscampaign', 'SMSCampaignController@list')->name('smscampaign.list');

/**
 * @OA\Get(path="/hub/smscampaign/{smscampaignId}",
 *   tags={"SMSCampaigns"},
 *   summary="Get single smscampaign",
 *   description="Get single smscampaign by smscampaign id",
 *   @OA\Parameter(name="smscampaignId", in="path", description="smscampaign id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SMSCampaignData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('smscampaign/{smscampaignId}', 'SMSCampaignController@getSMSCampaign')->where('smscampaignId', '[0-9]+')->name('smscampaign.input');


/**
 * @OA\Post(path="/hub/smscampaign",
 *   tags={"SMSCampaigns"},
 *   summary="Add a new smscampaign",
 *   description="Add a new smscampaign",
 *   operationId="store",
 *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/SMSCampaignInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SMSCampaignData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('smscampaign', 'SMSCampaignController@save')->name('smscampaign.input');

/**
 * @OA\post(path="/hub/smscampaign/{smscampaignId}",
 *   tags={"SMSCampaigns"},
 *   summary="Update a smscampaign",
 *   description="Update a smscampaign",
 *   @OA\Parameter(name="smscampaignId", in="path", description="SMSCampaign id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/SMSCampaignInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SMSCampaignData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('smscampaign/{smscampaignId}', 'SMSCampaignController@update')->where('smscampaignId', '[0-9]+')->name('smscampaign.input');

/**
 * @OA\patch(path="/hub/smscampaign/{smscampaignId}",
 *   tags={"SMSCampaigns"},
 *   summary="Update field(s) smscampaign",
 *   description="Update field(s) smscampaign",
 *   @OA\Parameter(name="smscampaignId", in="path", description="SMSCampaign id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/SMSCampaignInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SMSCampaignData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('smscampaign/{smscampaignId}', 'SMSCampaignController@update')->where('smscampaignId', '[0-9]+')->name('smscampaign.input');

/**
 * @OA\Get(path="/hub/smscampaign/totalcustomers",
 *   tags={"SMSCampaigns"},
 *   summary="Get Total Customers",
 *   description="Get total customers",
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/TotalCustomer")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('smscampaign/totalcustomers', 'SMSCampaignController@totalCustomers')->name('smscampaign.input');