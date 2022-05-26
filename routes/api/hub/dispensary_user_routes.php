<?php

/**
 * @OA\POST(
 *      tags = {"Dispensary User"},
 *      path = "/hub/setting/dispensary-user",
 *      summary = "Add Hub Dispensary User",
 *      description = "API for creating new Hub Setting Dispensary User",
 *      operationId="createDispensaryUser",
 *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/HubDispensaryUserInputData"))),
 *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/HubDispensaryUserData")),
 *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *      @OA\Response(response=403, description="Forbidden"),
 *      @OA\Response(response=404, description="Invalid Client Code"),
 *      @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('setting/dispensary-user', 'DispensaryUserController@save')->name('dispensary.user.input');


/**
 * @OA\Get(
 *   tags={"Dispensary User"},
 *   path="/hub/setting/dispensary-user",
 *   summary="Hub Dispensary User Listing.",
 *   description="List all hub dispensary user data",
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
 *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/HubDispensaryUserSortsOn")),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubDispensaryUserList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 *
 */
Route::get('setting/dispensary-user', 'DispensaryUserController@list')->name('dispensary.user.list');

/**
 * @OA\Get(path="/hub/setting/dispensary-user/{dispensaryUserId}",
 *   tags={"Dispensary User"},
 *   summary="Get single dispensary user",
 *   description="Get single dispensary user by dispensary user id",
 *   @OA\Parameter(name="dispensaryUserId", in="path", description="dispensary user id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/HubDispensaryUserData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('setting/dispensary-user/{dispensaryUserId}', 'DispensaryUserController@getDispensaryUser')->where('dispensaryUserId', '[0-9]+')->name('dispensary.user.input');

/**
 * @OA\post(path="/hub/setting/dispensary-user/{dispensaryUserId}",
 *   tags={"Dispensary User"},
 *   summary="Update a dispensary user",
 *   description="Update a dispensary user",
 *   @OA\Parameter(name="dispensaryUserId", in="path", description="Dispensary User id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/HubDispensaryUserInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/HubDispensaryUserData")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('setting/dispensary-user/{dispensaryUserId}', 'DispensaryUserController@update')->where('dispensaryUserId', '[0-9]+')->name('dispensary.user.input');

/**
 * @OA\patch(path="/hub/setting/dispensary-user/{dispensaryUserId}",
 *   tags={"Dispensary User"},
 *   summary="Update dispensary user status",
 *   description="Update dispensary user status",
 *   operationId="updatePatchDispensaryUser",
 *   @OA\Parameter(name="dispensaryUserId", in="path", description="Dispensary User id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/HubDispensaryUserPatchData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('setting/dispensary-user/{dispensaryUserId}', 'DispensaryUserController@update')->where('dispensaryUserId', '[0-9]+')->name('dispensary.user.status');

/**
 * @OA\Delete(path="/hub/setting/dispensary-user/{dispensaryUserId}",
 *   tags={"Dispensary User"},
 *   summary="Delete single dispensary user",
 *   description="Delete single dispensary user by id",
 *   @OA\Parameter(name="dispensaryUserId", in="path", description="Dispensary user id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('setting/dispensary-user/{dispensaryUserId}', 'DispensaryUserController@delete')->where('dispensaryUserId', '[0-9]+');

/**
 * @OA\Get(
 *   tags={"Dispensary User"},
 *   path="/hub/setting/dispensary-user/get-permission",
 *   summary="Hub Dispensary User Access Data.",
 *   description="List all dispensary user access data by role type",
 *   @OA\Parameter(in="query", name="type[]", @OA\Schema(ref="#/components/schemas/HubDispensaryUserRoleType")),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/PermissionList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('setting/dispensary-user/get-permission', 'DispensaryUserController@getPermission')->name('dispensary.user.input');