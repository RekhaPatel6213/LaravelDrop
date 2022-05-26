<?php
    /**
     * @OA\Post(
     *   tags={"Product Audit"},
     *   path="/hub/audit/products",
     *   summary="Product Listing.",
     *   description="List All Products",
     * 	 @OA\Parameter(name="model_type", in="query", description="Model Type",  required=true, @OA\Schema(type="string", enum={"Inventory","Territory","Driver","Category"})),
     *   @OA\Parameter(name="model_id", in="query", description="Model Id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/AuditProducts")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('audit/products', 'AuditController@getProducts');

    /**
     * @OA\Get(
     *    tags = {"Product Audit"},
     *    path = "/hub/audit",
     *    summary = "List of all Audit",
     *    description = "API for List of all Audit",
     *    operationId="AuditList",
     *    @OA\Parameter(in="query", name="search", description="Search Using Type and Dispensary user"),
     *    @OA\Parameter(ref="#/components/parameters/sort"),
     *    @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/AuditSortsOn")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/AuditList")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('audit', 'AuditController@list')->name('audit.list');

    /**
     * @OA\Post(
     *    tags = {"Product Audit"},
     *    path = "/hub/audit",
     *    summary = "Audit Store",
     *    description = "API for Audit Store",
     *    operationId="auditStore",
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/AuditInputData"))),
     *    @OA\Response(response=200, description="Audit Store Success"),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('audit', 'AuditController@store')->name('audit.store');

    /**
     * @OA\Get(
     *   tags={"Product Audit"},
     *   path="/hub/audit/{auditId}",
     *   summary="Get Audit Details",
     *   description="Get Audit Details",
     *   @OA\Parameter(name="auditId", in="path", description="Audit Id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Get Single Audit Details", @OA\JsonContent(ref="#/components/schemas/AuditDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('audit/{auditId}', 'AuditController@get')->where('auditId', '[0-9]+')->name('audit.get');

    /**
     * @OA\Get(
     *   tags={"Product Audit"},
     *   path="/hub/audit/export/{auditId}",
     *   summary="Export Audit",
     *   description="Export Audit",
     * 	 @OA\Parameter(name="auditId", in="path", description="Audit Id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200,description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('audit/export/{auditId}', 'AuditController@export')->where('auditId', '[0-9]+')->name('audit.export');

 