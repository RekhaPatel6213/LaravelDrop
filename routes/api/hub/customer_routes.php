<?php

    /**
     * @OA\Get(path="/hub/customer",
     *   tags={"Customer"},
     *   summary="Customer Listing.",
     *   description="List all customer data",
     *   @OA\Parameter(
     *         description="Search String - Enter keyword to search by id, name, email, phone",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/DispensaryCustomerSortsOn")),
     *   @OA\Parameter(in="query", name="customerStatus", @OA\Schema(ref="#/components/schemas/DispensaryCustomerStatus")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/DispensaryCustomerList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('customer', 'Customer\CustomerController@dispensaryCustomerList');

    /**
     * @OA\Post(path="/hub/customer",
     *   tags={"Customer"},
     *   summary="Add a new customer",
     *   description="Add a new customer,
     *      If customer with same phone number will added to other dispensary then it will append dispensary list
     *     ",
     *   operationId="store",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/CustomerInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('customer', 'Customer\CustomerController@store');

    /**
     * @OA\Patch(path="/hub/customer/{customerId}",
     *   tags={"Customer"},
     *   summary="Update single customer",
     *   description="Update single customer by providing customer ids",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/CustomerUpdateInputData"))),
     *   @OA\Parameter(name="customerId", in="path", description="Customer Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    Route::patch('customer/{customerId}', 'Customer\CustomerController@update')->where('customerId', '[0-9]+')->name('customers.update');

    /**
     * @OA\Post(path="/hub/customer/import-preview",
     *   tags={"Customer"},
     *   summary="Import preview customer",
     *   description="Import preview customer",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/CustomerImportData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('customer/import-preview', 'Customer\CustomerController@importPreviewCustomers');

    /**
     * @OA\Post(path="/hub/customer/import/{previewId}",
     *   tags={"Customer"},
     *   summary="Import customer",
     *   description="Import customer",
     *   @OA\Parameter(name="previewId", in="path", description="Preview Id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('customer/import/{previewId}', 'Customer\CustomerController@importCustomers')->name('customers.import');

    /**
     * @OA\Get(path="/hub/customer/export",
     *   tags={"Customer"},
     *   summary="export customer",
     *   description="export customer",
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::Get('customer/export', 'Customer\CustomerController@exportCustomers');

    /**
     * @OA\Get(path="/hub/customer/import-history",
     *   tags={"Customer"},
     *   summary="Import history",
     *   description="Import History",
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('customer/import-history', 'Customer\CustomerController@importHistory');

    /**
     * @OA\Get(path="/hub/customer/import-details/{previewId}",
     *   tags={"Customer"},
     *   summary="Import history details",
     *   description="Import History details",
     *   @OA\Parameter(name="previewId", in="path", description="Preview Id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('customer/import-details/{previewId}', 'Customer\CustomerController@importDetails')->name('customers.import_details');

    /**
     * @OA\Post(path="/hub/customer/attach-documents",
     *   tags={"Customer"},
     *   summary="Attach documents",
     *   description="
     *           Driving Licence / Passport : single unit - overwritable |
     *           Medical Recommendation : single unit - overwritable |
     *           Other : Multiple - One unit at a time
     *   ",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/CustomerDocumentsData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('customer/attach-documents', 'Customer\CustomerController@attachDocuments')->name('customers.attach');

    /**
     * @OA\Delete(path="/hub/customer/{customerId}",
     *   tags={"Customer"},
     *   summary="Delete single dispensary customer",
     *   description="Delete single dispensary customer by customer id",
     *   @OA\Parameter(name="customerId", in="path", description="Customer id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('customer/{customerId}', 'Customer\CustomerController@deleteDispensaryCustomer')->where('customerId', '[0-9]+')->name('disp_customer.delete');
