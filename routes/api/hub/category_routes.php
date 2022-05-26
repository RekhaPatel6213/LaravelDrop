<?php
	/**
     * @OA\Get(
     *      tags = {"Categories"},
     *      path = "/hub/category",
     *      summary = "List of all Category",
     *      description = "API for List of Category",
     *      operationId="categoryList",
     *      @OA\Parameter(in="query", name="search", description="Search Using Category Name"),
     *      @OA\Parameter(ref="#/components/parameters/sort"),
     *      @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/CategorySortsOn")),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/CategoryList")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
	Route::get('category', 'CategoryController@list');

    /** @OA\Get(
     *      tags = {"Categories"},
     *      path = "/hub/sub-category",
     *      summary = "List of all Category",
     *      description = "API for List of Category",
     *      operationId="subCategoryList",
     *      @OA\Parameter(in="query", name="parent_id", description="List all the Category with parent id."),
     *      @OA\Parameter(in="query", name="search", description="Search Using Category Name"),
     *      @OA\Parameter(ref="#/components/parameters/sort"),
     *      @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/CategorySortsOn")),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SubCategoryList")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('sub-category', 'CategoryController@subCategoryList');

    /**
     * @OA\Post(path="/hub/category/{categoryId}",
     *      tags={"Categories"},
     *      summary="Dispensary Category Update Form",
     *      description="Update Admin Dispensary Category Data",
     *      operationId="categoryUpdate",
     *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/CategoryInputData"))),
     *      @OA\Parameter(name="categoryId", in="path", description="Category ID", required=true, example="1"),
     *      @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/CategoryDetail")),
     *      @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('category/{categoryId}', 'CategoryController@update')->where('categoryId', '[0-9]+');