<?php
/**
 * @OA\Get(path="/hub/inventory",
 *   tags={"Inventories"},
 *   summary="Inventory Listing.",
 *   description="List All Inventory Data",
 *   @OA\Parameter(in="query", name="name", description="Search Using Inventory Name"),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/InventoryList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('inventory', 'InventoryController@list')->name('inventory.list');

/**
 * @OA\POST(
 *      tags = {"Inventories"},
 *      path = "/hub/inventory",
 *      summary = "Create New Inventory",
 *      description = "API for creating new inventory",
 *      operationId="createInventory",
 *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/InventoryInputData"))),
 *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/InventoryDetail")),
 *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *      @OA\Response(response=403, description="Forbidden"),
 *      @OA\Response(response=404, description="Invalid Client Code"),
 *      @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('inventory', 'InventoryController@save');

/**
 * @OA\Get(path="/hub/inventory/{inventoryId}",
 *   tags={"Inventories"},
 *   summary="Get Inventory",
 *   description="Get Inventory Data",
 *   @OA\Parameter(name="inventoryId", in="path", description="Inventory ID", required=true, example="1"),
 *   @OA\Response(response=200, description="Get Single Inventory Record",@OA\JsonContent(ref="#/components/schemas/InventoryDetail")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Lens Data Not Found"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('inventory/{inventoryId}', 'InventoryController@getInventory')->where('inventoryId', '[0-9]+');

/**
 * @OA\Put(path="/hub/inventory/{inventoryId}",
 *   tags={"Inventories"},
 *   summary="Inventory Update Form",
 *   description="Update Inventory",
 *   requestBody={"$ref": "#/components/requestBodies/InventoryRequest"},
 *   @OA\Parameter(name="inventoryId", in="path", description="Inventory ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/InventoryDetail")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::put('inventory/{inventoryId}', 'InventoryController@update')->where('inventoryId', '[0-9]+');

/**
 * @OA\Patch(path="/hub/inventory/{inventoryId}",
 *   tags={"Inventories"},
 *   summary="Inventory Update Form",
 *   description="Update Inventory",
 *   requestBody={"$ref": "#/components/requestBodies/InventoryRequest"},
 *   @OA\Parameter(name="inventoryId", in="path", description="Inventory ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/InventoryDetail")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('inventory/{inventoryId}', 'InventoryController@update')->where('inventoryId', '[0-9]+');

/**
 * @OA\Delete(path="/hub/inventory/{inventoryId}",
 *   tags={"Inventories"},
 *   summary="Delete Inventory",
 *   description="Delete Inventory By ID",
 *   @OA\Parameter(name="inventoryId", in="path", description="Inventory ID", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('inventory/{inventoryId}', 'InventoryController@delete')->where('inventoryId', '[0-9]+');
