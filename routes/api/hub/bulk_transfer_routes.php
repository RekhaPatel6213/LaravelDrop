<?php
    /**
     * @OA\Post(
     *   tags={"Bulk Transfer Inventories"},
     *   path="/hub/bulk-transfer/products",
     *   summary="Product Listing.",
     *   description="List All Products has Inventory Id",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/BulkTransferInventoryIds"))),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/BulkProducts")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('bulk-transfer/products', 'BulkTransferController@getProducts');

    /**
     * @OA\Post(
     *    tags = {"Bulk Transfer Inventories"},
     *    path = "/hub/bulk-transfer",
     *    summary = "Bulk Transfer Inventory",
     *    description = "API for Bulk Transfer Inventory",
     *    operationId="bulkTransferInventory",
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/BulkTransferInputData"))),
     *    @OA\Response(response=200, description="Bulk Transfer Success"),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('bulk-transfer', 'BulkTransferController@transfer');

    /**
     * @OA\Get(
     *   tags={"Bulk Transfer Inventories"},
     *   path="/hub/bulk-transfer/{transferId}",
     *   summary="Get Bulk Transfer Details",
     *   description="Get Bulk Transfer Details",
     *   @OA\Parameter(name="transferId", in="path", description="Transfer ID", required=true, example="1"),
     *   @OA\Response(response=200, description="Get Single Bulk Transfer Details",@OA\JsonContent(ref="#/components/schemas/BulkTransferDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('bulk-transfer/{transferId}', 'BulkTransferController@get')->where('transferId', '[0-9]+');

    

    