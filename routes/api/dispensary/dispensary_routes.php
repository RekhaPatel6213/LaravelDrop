<?php
    /**
     * @OA\Get(path="/admin/dispensary",
     *   tags={"Dispensary"},
     *   summary="Dispensary Listing.",
     *   description="List all dispensary data",
     *   @OA\Parameter(
     *         description="Search String - Enter keyword to search by id, name, email, phone, address",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/DispensarySortsOn")),
     *   @OA\Parameter(in="query", name="dispensaryStatus", @OA\Schema(ref="#/components/schemas/DispensaryStatus")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/DispensaryList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('dispensary', 'Dispensary\DispensaryController@list');

    /**
     * @OA\Get(path="/admin/dispensary/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Get single dispensary",
     *   description="Get single dispensary by dispensary id",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('dispensary/{dispensaryId}', 'Dispensary\DispensaryController@getDispensary')->where('dispensaryId', '[0-9]+');


    /**
     * @OA\Post(path="/admin/dispensary",
     *   tags={"Dispensary"},
     *   summary="Add a new dispensary",
     *   description="Add a new dispensary",
     *   operationId="store",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/DispensaryInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('dispensary', 'Dispensary\DispensaryController@store');

    /**
     * @OA\Post(path="/admin/dispensary/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Update single dispensary",
     *   description="Update single dispensary by providing dispensary id",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/DispensaryInputData"))),
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    Route::post('dispensary/{dispensaryId}', 'Dispensary\DispensaryController@update')->where('dispensaryId', '[0-9]+');


    /**
     * @OA\Delete(path="/admin/dispensary/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Delete single dispensary",
     *   description="Delete single dispensary by dispensary id",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('dispensary/{dispensaryId}', 'Dispensary\DispensaryController@delete')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Patch(path="/admin/dispensary/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Update dispensary",
     *   description="Update dispensary by dispensary id",
     *   requestBody={"$ref": "#/components/requestBodies/DispensaryStatusUpdateRequest"},
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('dispensary/{dispensaryId}', 'Dispensary\DispensaryController@update')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Get(path="/admin/dispensary/notes/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Dispensary notes listing.",
     *   description="List all notes of a dispensary",
     *   @OA\Parameter(
     *         description="Dispensary Id",
     *         example="1",
     *         in="path",
     *         name="dispensaryId",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryNotesInputDataRespArr")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('dispensary/notes/{dispensaryId}', 'Dispensary\DispensaryController@getNotes')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Post(path="/admin/dispensary/notes",
     *   tags={"Dispensary"},
     *   summary="Add a note to dispensary",
     *   description="Add a note to dispensary",
     *   operationId="addNote",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/DispensaryNotesInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DispensaryNotesInputDataResp")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('dispensary/notes', 'Dispensary\DispensaryController@addNote');

    /**
     * @OA\Post(path="/admin/dispensary/send-mail/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Send Mail For Hub & Dispatch Reset password link",
     *   description="Send Mail For Hub & Dispatch Reset password link",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    Route::post('dispensary/send-mail/{dispensaryId}', 'Dispensary\DispensaryController@sendMail')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Get(path="/admin/dispensary/access/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Get All Dispensary Access Details",
     *   description="Get All Dispensary Access Details",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Get All Dispensary Access Details", @OA\JsonContent(ref="#/components/schemas/DispensaryAccess")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('dispensary/access/{dispensaryId}', 'Dispensary\AccessController@getAccess')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Put(path="/admin/dispensary/access/{dispensaryId}",
     *   tags={"Dispensary"},
     *   summary="Dispensary Access Detail Update Form",
     *   description="Update Dispensary Access Detail",
     *   requestBody={"$ref": "#/components/requestBodies/DispensaryAccessUpdate"},
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('dispensary/access/{dispensaryId}', 'Dispensary\AccessController@updateAccess')->where('dispensaryId', '[0-9]+');
