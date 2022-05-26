<?php
/**
 * @OA\Get(path="/hub/vendor",
 *   tags={"Vendors"},
 *   summary="Vendor Listing.",
 *   description="List All Vendor Data",
 *   @OA\Parameter(
 *         description="Search By Vendor Name - Enter keyword to search",
 *         in="query",
 *         name="search",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   @OA\Parameter(ref="#/components/parameters/sort"),

 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/VendorList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('vendor', 'VendorController@list');

/**
 * @OA\POST(
 *      tags = {"Vendors"},
 *      path = "/hub/vendor",
 *      summary = "Create New Vendor",
 *      description = "API for creating new vendor",
 *      operationId="createVendor",
 *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/VendorInputData"))),
 *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *      @OA\Response(response=403, description="Forbidden"),
 *      @OA\Response(response=404, description="Invalid Client Code"),
 *      @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('vendor', 'VendorController@save');

/**
 * @OA\Get(path="/hub/vendor/{vendorId}",
 *   tags={"Vendors"},
 *   summary="Get Vendor",
 *   description="Get Vendor Data",
 *   @OA\Parameter(name="vendorId", in="path", description="Vendor ID", required=true, example="1"),
 *   @OA\Response(response=200, description="Get Single Vendor Record",@OA\JsonContent(ref="#/components/schemas/Vendor")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Lens Data Not Found"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('vendor/{vendorId}', 'VendorController@getVendor')->where('vendorId', '[0-9]+');

/**
 * @OA\Put(path="/hub/vendor/{vendorId}",
 *   tags={"Vendors"},
 *   summary="Vendor Update Form",
 *   description="Update Vendor",
 *   requestBody={"$ref": "#/components/requestBodies/VendorRequest"},
 *   @OA\Parameter(name="vendorId", in="path", description="Vendor ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/Vendor")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::put('vendor/{vendorId}', 'VendorController@update')->where('vendorId', '[0-9]+');

/**
 * @OA\Patch(path="/hub/vendor/{vendorId}",
 *   tags={"Vendors"},
 *   summary="Vendor Update Form",
 *   description="Update Vendor",
 *   requestBody={"$ref": "#/components/requestBodies/VendorRequest"},
 *   @OA\Parameter(name="vendorId", in="path", description="Vendor ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/Vendor")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('vendor/{vendorId}', 'VendorController@update')->where('vendorId', '[0-9]+');

/**
 * @OA\Delete(path="/hub/vendor/{vendorId}",
 *   tags={"Vendors"},
 *   summary="Delete Vendor",
 *   description="Delete Vendor By ID",
 *   @OA\Parameter(name="vendorId", in="path", description="Vendor ID", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation"),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('vendor/{vendorId}', 'VendorController@delete')->where('vendorId', '[0-9]+');