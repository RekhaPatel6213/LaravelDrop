<?php
    //TO NOTE : we will be utilizing this same controller for storing and getting settings
    /**
     * @OA\Get(path="/admin/settings",
     *   tags={"Admin Settings"},
     *   summary="Get All Admin Settings Details",
     *   description="Get All Admin Settings Detail Data",
     *   @OA\Response(response=200, description="Get All Admin Settings Record", @OA\JsonContent(ref="#/components/schemas/WMAttributeDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('settings', 'SettingsController@getSettings');

    /**
     * @OA\Put(path="/admin/settings",
     *   tags={"Admin Settings"},
     *   summary="Admin Settings Detail Update Form",
     *   description="Update Admin Settings Detail",
     *   requestBody={"$ref": "#/components/requestBodies/WeedmapsSettings"},
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/WMAttributeDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('settings', 'SettingsController@updateSettings');