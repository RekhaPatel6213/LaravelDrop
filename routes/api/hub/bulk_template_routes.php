<?php
    /**
     * @OA\Get(
     *    tags = {"Bulk Transfer Template"},
     *    path = "/hub/bulk-template",
     *    summary = "List of all Template",
     *    description = "API for List of all Template",
     *    operationId="BulkTemplateList",
     *    @OA\Parameter(in="query", name="search", description="Search Using Template Name, Inventory Ids and Products"),
     *    @OA\Parameter(ref="#/components/parameters/sort"),
     *    @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/BulkTemplateSortsOn")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/BulkTemplateList")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
     Route::get('bulk-template', 'BulkTemplateController@list');

    /**
     * @OA\Post(
     *    tags = {"Bulk Transfer Template"},
     *    path = "/hub/bulk-template",
     *    summary = "Create New Template",
     *    description = "API for creating new Template",
     *    operationId="createBulkTemplate",
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/BulkTemplateInputData"))),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/BulkTemplateSingle")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('bulk-template', 'BulkTemplateController@create');

    /**
     * @OA\Get(
     *    tags = {"Bulk Transfer Template"},
     *    path="/hub/bulk-template/{templateId}",
     *    summary="Get Template Details",
     *    description="API for Getting Template Details",
     *    @OA\Parameter(in="path", name="templateId", description="Template ID", required=true, example="1"),
     *    @OA\Response(response=200, description="Get Single Template Details",@OA\JsonContent(ref="#/components/schemas/BulkTemplateSingle")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Lens Data Not Found"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('bulk-template/{templateId}', 'BulkTemplateController@get')->where('templateId', '[0-9]+');

    /**
     * @OA\Post(
     *    tags = {"Bulk Transfer Template"},
     *    path = "/hub/bulk-template/{templateId}",
     *    summary = "Updating Single Template",
     *    description = "API for Updating Template By ID",
     *    operationId="updateBulkTemplate",
     *    @OA\Parameter(in="path", name="templateId", description="Template ID", required=true, example="1"),
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/BulkTemplateInputData"))),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/BulkTemplateSingle")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('bulk-template/{templateId}', 'BulkTemplateController@update')->where('templateId', '[0-9]+');

    /**
     * @OA\Patch(
     *    tags = {"Bulk Transfer Template"},
     *    path="/hub/bulk-template/{templateId}",
     *    summary="Update Single Template",
     *    description="API for Updating Template By ID",
     *    operationId="updatePatchBulkTemplate",
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/BulkTemplatePatchData"))),
     *    @OA\Parameter(name="templateId", in="path", description="Template ID", required=true, example="1"),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/BulkTemplateSingle")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('bulk-template/{templateId}', 'BulkTemplateController@update')->where('templateId', '[0-9]+');

    /**
     * @OA\Delete(
     *    tags = {"Bulk Transfer Template"},
     *    path="/hub/bulk-template/{templateId}",
     *    summary="Delete Single Template",
     *    description="API for Deleting Template By ID",
     *    operationId="deleteBulkTemplate",
     *    @OA\Parameter(name="templateId", in="path", description="Template ID", required=true, example="1", @OA\Schema(type="integer")),
     *    @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('bulk-template/{templateId}', 'BulkTemplateController@delete')->where('templateId', '[0-9]+');