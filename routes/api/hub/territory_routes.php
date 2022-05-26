<?php
    /**
     * @OA\Get(path="/hub/territory",
     *   tags={"Territory"},
     *   summary="Territories Listing.",
     *   description="List all territories data",
     *   @OA\Parameter(
     *         description="Search String - Enter keyword to search by name",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/TerritorySortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/TerritoriesList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('territory', 'TerritoryController@list');

    /**
     * @OA\Post(path="/hub/territory",
     *   tags={"Territory"},
     *   summary="Add a new territory",
     *   description="Add a new territory",
     *   operationId="store",
     *   requestBody={"$ref": "#/components/requestBodies/TerritoryData"},
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/TerritoryInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('territory', 'TerritoryController@store');

    /**
     * @OA\Get(path="/hub/territory/{id}",
     *   tags={"Territory"},
     *   summary="Get Territory Data",
     *   description="Get Territory Data",
     *   @OA\Parameter(name="id", in="path", description="Territory ID", required=true, example="1"),
     *   @OA\Response(response=200, description="Get Single Territory Data",@OA\JsonContent(ref="#/components/schemas/TerritoryInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('territory/{id}', 'TerritoryController@get')->where('id', '[0-9]+')->name('territory.view');

    /**
     * @OA\Put(path="/hub/territory/{id}",
     *   tags={"Territory"},
     *   summary="Update territory",
     *   description="Update territory",
     *   operationId="update",
     *   @OA\Parameter(name="id", in="path", description="Territory Id", required=true, example="1", @OA\Schema(type="integer")),
     *   requestBody={"$ref": "#/components/requestBodies/TerritoryData"},
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/TerritoryInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('territory/{id}', 'TerritoryController@update')->where('id', '[0-9]+');

    /**
     * @OA\Delete(path="/hub/territory/{id}",
     *   tags={"Territory"},
     *   summary="Delete Territory",
     *   description="Delete Territory By ID",
     *   @OA\Parameter(name="id", in="path", description="Territory ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('territory/{id}', 'TerritoryController@delete')->where('id', '[0-9]+');

    /**
     * @OA\Get(path="/hub/ajax-territories",
     *   tags={"Territory"},
     *   summary="ajax-territories",
     *   description="ajax-territories",
     *   operationId="ajaxTerritories",
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/AjaxTerritoriesList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('ajax-territories', 'TerritoryController@ajaxTerritories');