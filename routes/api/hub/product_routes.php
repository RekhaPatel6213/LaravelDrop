<?php
//Route::group(['middleware' => ['permission:PRODUCTS|DASHBOARD']], function () {
     /**
     * @OA\Get(
     *    tags = {"Products"},
     *    path = "/hub/product",
     *    summary = "List of all Product",
     *    description = "API for List of all Product",
     *    operationId="ProductList",
     *    @OA\Parameter(in="query", name="search", description="Search Using Product Name, Brand and Category"),
     *    @OA\Parameter(in="query", name="state", @OA\Schema(ref="#/components/schemas/ProductState")),
     *    @OA\Parameter(ref="#/components/parameters/sort"),
     *    @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/ProductSortsOn")),
     *    @OA\Parameter(in="query", name="includes", description="includes", style="form", explode=false, @OA\Schema(ref="#/components/schemas/ProductInclude")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/ProductList")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
     Route::get('product', 'ProductController@list');

     /**
     * @OA\Get(
     *    tags = {"Products"},
     *    path = "/hub/product/all",
     *    summary = "List of all Product with details",
     *    description = "API for List of all Product with details",
     *    operationId="ProductListDetails",
     *    @OA\Parameter(in="query", name="search", description="Search Using Product Name, Brand and Category"),
     *    @OA\Parameter(in="query", name="state", @OA\Schema(ref="#/components/schemas/ProductState")),
     *    @OA\Parameter(ref="#/components/parameters/sort"),
     *    @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/ProductSortsOn")),
     *    @OA\Parameter(in="query", name="includes", description="includes", style="form", explode=false, @OA\Schema(ref="#/components/schemas/ProductInclude")),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/ProductListDetail")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
     Route::get('product/all', 'ProductController@allList');

     /**
     * @OA\Post(
     *    tags = {"Products"},
     *    path = "/hub/product",
     *    summary = "Create New Product",
     *    description = "API for creating new product",
     *    operationId="createProduct",
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProductInputData"))),
     *    @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/ProductGetDetail")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('product', 'ProductController@create');

    /**
     * @OA\Get(
     *    tags={"Products"},
     *    path="/hub/product/{productId}",
     *    summary="Get Product Details",
     *    description="API for Getting Product Details",
     *    @OA\Parameter(in="path", name="productId", description="Product ID", required=true, example="1"),
     *    @OA\Parameter(in="query", name="includes", description="includes", style="form", explode=false, @OA\Schema(ref="#/components/schemas/ProductInclude")),
     *    @OA\Response(response=200, description="Get Single Product Details",@OA\JsonContent(ref="#/components/schemas/ProductGetDetail")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Lens Data Not Found"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/{productId}', 'ProductController@get')->where('productId', '[0-9]+');

    /**
     * @OA\Post(
     *    tags = {"Products"},
     *    path = "/hub/product/{productId}",
     *    summary = "Updating Single Product",
     *    description = "API for Updating Product By ID",
     *    operationId="updateProduct",
     *    @OA\Parameter(in="path", name="productId", description="Product ID", required=true, example="1"),
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/ProductInputData"))),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/ProductGetDetail")),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('product/{productId}', 'ProductController@update')->where('productId', '[0-9]+');

    /**
     * @OA\Patch(
     *    tags={"Products"},
     *    path="/hub/product/{productId}",
     *    summary="Update Single Product",
     *    description="API for Updating Product By ID",
     *    operationId="updatePatchProduct",
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProductPatchData"))),
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1"),
     *    @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/ProductGetDetail")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('product/{productId}', 'ProductController@update')->where('productId', '[0-9]+');

    /**
     * @OA\Patch(
     *    tags={"Products"},
     *    path="/hub/product/all",
     *    summary="Update Multiple Product",
     *    description="API for Updating Multiple Product By IDs",
     *    @OA\Parameter(in="query", name="product_ids", description="Update List of all the Product with specified id."),
     *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProductPatchData"))),
     *    @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('product/all', 'ProductController@updateAll');

    /**
     * @OA\Delete(
     *    tags={"Products"},
     *    path="/hub/product/{productId}",
     *    summary="Delete Single Product",
     *    description="API for Deleting Product By ID",
     *    @OA\Parameter(name="productId", in="path", description="Product ID", required=true, example="1", @OA\Schema(type="integer")),
     *    @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('product/{productId}', 'ProductController@delete')->where('productId', '[0-9]+');

    /**
     * @OA\Delete(
     *    tags={"Products"},
     *    path="/hub/product/all",
     *    summary="Delete Multiple Product",
     *    description="API for Deleting Multiple Product By IDs",
     *    @OA\Parameter(in="query", name="product_ids", description="delete List of all the Product with specified id."),
     *    @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('product/all', 'ProductController@deleteAll');

    /**
     * @OA\Get(
     *    tags={"Products"},
     *    path="/hub/product/variant",
     *    summary="Get Product Variant Details",
     *    description="API for Getting Product Variant Details",
     *    @OA\Parameter(in="query", name="taxonomy", @OA\Schema(ref="#/components/schemas/VariantSortsOn")),
     *    @OA\Response(response=200, description="Get Variant Details",@OA\JsonContent(ref="#/components/schemas/VariantList")),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Lens Data Not Found"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/variant', 'ProductController@variant')->where('productId', '[0-9]+');

    /**
     * @OA\Get(
     *   tags={"Products"},
     *   path="/hub/product/export/csv",
     *   summary="Export Product",
     *   description="Export Product",
     *   @OA\Response(response=200,description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/export/{type}', 'ProductController@export');

    /**
     * @OA\Get(
     *   tags={"Products"},
     *   path="/hub/product/export/pdf",
     *   summary="Export Product",
     *   description="Export Product",
     *   @OA\Response(response=200,description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/export/{type}', 'ProductController@export');

    /**
     * @OA\Get(
     *   tags={"Products"},
     *   path="/hub/product/import-history",
     *   summary="Product Import history",
     *   description="Product Import History",
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/ImportDataList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/import-history', 'ProductController@importHistory')->name('product.import.history');

    /**
     * @OA\Post(
     *   tags={"Products"},
     *   path="/hub/product/import",
     *   summary="Import Preview Product",
     *   description="Import Preview Product",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/ProductImportData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/ImportDetails")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('product/import', 'ProductController@import')->name('product.import');

    /**
     * @OA\Post(
     *   tags={"Products"},
     *   path="/hub/product/import/{previewId}",
     *   summary="Import Product",
     *   description="Import Product",
     *   @OA\Parameter(name="previewId", in="path", description="Preview Id", required=true, example="1", @OA\Schema(type="integer")),
     * @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ImportInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('product/import/{previewId}', 'ProductController@importPreview');

    /**
     * @OA\Get(
     *   tags={"Products"},
     *   path="/hub/product/import-details/{previewId}",
     *   summary="Import History Details",
     *   description="Import History Details",
     *   @OA\Parameter(name="previewId", in="path", description="Preview Id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/ImportDetailView")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/import-details/{previewId}', 'ProductController@importDetail')->name('product.import.detail');

    /**
     * @OA\Get(
     *    tags={"Product Master"},
     *    path="/hub/product/list",
     *    summary="Get Product list",
     *    description="API for Get Product list",
     *    @OA\Response(response=200, description="successful operation"),
     *    @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Lens Data Not Found"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('product/list', 'ProductController@ajaxList');
//});