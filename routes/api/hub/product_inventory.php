<?php
    /**
     * @OA\Get(
     *    tags = {"Products Inventories"},
     *    path = "/hub/product/inventory/{productId}",
     *    summary = "Product Inventory Detail",
     *    description = "API for Product Inventory Detail",
     *    operationId="ProductInventoryDetail",
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1",@OA\Schema(type="integer")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/ProductInventoryList")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/inventory/{productId}', 'ProductInventoryController@detail')->where('productId', '[0-9]+');

    /**
     * @OA\Get(
     *    tags = {"Products Inventories"},
     *    path = "/hub/product/inventory/{productId}/{modelId}",
     *    summary = "Product Inventory Detail of Single Model Type",
     *    description = "API for Product Inventory Detail of Single Model Type",
     *    operationId="ProductInventorySingleDetail",
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1",@OA\Schema(type="integer")),  
     *    @OA\Parameter(name="modelId", in="path", description="Model Type ID", required=true, example="1",@OA\Schema(type="integer")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/ProductInventorySingle")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/inventory/{productId}/{modelId?}', 'ProductInventoryController@detail')->where(['productId' => '[0-9]+', 'modelId' => '[0-9]+' ]);

    /**
     * @OA\Get(
     *    tags = {"Products Inventories"},
     *    path = "/hub/product/inventory-log",
     *    summary = "List of all Product Inventory Log",
     *    description = "API for List of all Product Inventory Log",
     *    operationId="ProductInventoryLog",
     *    @OA\Parameter(in="query", name="search", description="Search Using Product Name, Brand and Category"),
     *    @OA\Parameter(ref="#/components/parameters/sort"),
     *    @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/ProductInventorySortsOn")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/InventoryLogList")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/inventory-log', 'ProductInventoryController@logList');

    /**
     * @OA\Patch(
     *    tags={"Products Inventories"},
     *    path="/hub/product/add-stock/{productId}",
     *    summary="Add Product Stock",
     *    description="API for adding Product Stock By ID",
     *    operationId="addProductStock",
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1",@OA\Schema(type="integer")),
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProductStock"))),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('product/add-stock/{productId}', 'ProductInventoryController@addStock')->where('productId', '[0-9]+');

    /**
     * @OA\Patch(
     *    tags={"Products Inventories"},
     *    path="/hub/product/inventory/{productId}",
     *    summary="Add Product Inventory",
     *    description="API for adding Product Inventory By ID",
     *    operationId="addProductInventory",
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1",@OA\Schema(type="integer")),
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProductInventoryData"))),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('product/inventory/{productId}', 'ProductInventoryController@store')->where('productId', '[0-9]+');

    /**
     * @OA\Patch(
     *    tags={"Products Inventories"},
     *    path="/hub/product/inventory-reallocate/{inventoryId}",
     *    summary="Reallocate Product Inventory",
     *    description="API for Reallocating Product Inventory By ID",
     *    operationId="ReallocateProductInventory",
     * 	  @OA\Parameter(name="inventoryId", in="path", description="Inventory ID", required=true, example="1", @OA\Schema(type="integer")),
     * 	  @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ReallocateInventoryData"))),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('product/inventory-reallocate/{inventoryId}', 'ProductInventoryController@reallocate')->where('inventoryId', '[0-9]+');

    /**
     * @OA\DELETE(
     *    tags={"Products Inventories"},
     *    path="/hub/product/inventory/{productId}/{modelId}",
     *    summary="Delete Product Inventory",
     *    description="API for Deleting Product Model By ID",
     *    operationId="deleteProductInventory",
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1",@OA\Schema(type="integer")),
     *    @OA\Parameter(name="modelId", in="path", description="Model ID", required=true, example="1",@OA\Schema(type="integer")),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('product/inventory/{productId}/{modelId}', 'ProductInventoryController@delete')->where(['productId' => '[0-9]+', 'modelId' => '[0-9]+' ]);;