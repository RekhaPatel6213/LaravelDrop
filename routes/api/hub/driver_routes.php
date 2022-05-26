<?php
    /**
     * @OA\Get(path="/hub/driver",
     *   tags={"Driver"},
     *   summary="Driver Listing.",
     *   description="List all driver data",
     *   @OA\Parameter(
     *         description="Search String - Enter keyword to search by id, name, email, phone",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/DriverUserSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/DriverUserList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('driver', 'DriverUserController@list')->name('drivers.list');

    /**
     * @OA\Get(path="/hub/driver/{driverId}",
     *   tags={"Driver"},
     *   summary="Get single driver",
     *   description="Get single driver by driver id",
     *   @OA\Parameter(name="driverId", in="path", description="driver id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DriverUser")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('driver/{driverId}', 'DriverUserController@getdriver')->where('driverId', '[0-9]+')->name('drivers.get');


    /**
     * @OA\Post(path="/hub/driver",
     *   tags={"Driver"},
     *   summary="Add a new driver",
     *   description="Add a new driver",
     *   operationId="store",
     *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/DriverUserInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DriverUser")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('driver', 'DriverUserController@store')->name('drivers.save');

    /**
     * @OA\post(path="/hub/driver/{driverId}",
     *   tags={"Driver"},
     *   summary="Update a driver",
     *   description="Update a driver",
     *   @OA\Parameter(name="driverId", in="path", description="Driver id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/DriverUserInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DriverUser")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('driver/{driverId}', 'DriverUserController@update')->where('driverId', '[0-9]+')->name('drivers.update');


    /**
     * @OA\patch(path="/hub/driver/{driverId}",
     *   tags={"Driver"},
     *   summary="Update filed(s) driver",
     *   description="Update filed(s) driver",
     *   @OA\Parameter(name="driverId", in="path", description="Driver id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/DriverUserInputDataPatch"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/DriverUser")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('driver/{driverId}', 'DriverUserController@update')->where('driverId', '[0-9]+')->name('drivers.patch');


    /**
     * @OA\Delete(path="/hub/driver/{driverId}",
     *   tags={"Driver"},
     *   summary="Delete single driver",
     *   description="Delete single driver by driver id",
     *   @OA\Parameter(name="driverId", in="path", description="Driver id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('driver/{driverId}', 'DriverUserController@delete')->where('driverId', '[0-9]+')->name('drivers.delete');

