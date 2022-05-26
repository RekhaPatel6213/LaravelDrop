<?php
    /**
     * @OA\Get(path="/admin/customer",
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
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/CustomerSortsOn")),
     *   @OA\Parameter(in="query", name="customerStatus", @OA\Schema(ref="#/components/schemas/CustomerStatus")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/CustomerList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('customer', 'Customer\CustomerController@list')->name('customers.list');

    /**
     * @OA\Get(path="/admin/customer/{customerId}",
     *   tags={"Customer"},
     *   summary="Get single customer",
     *   description="Get single customer by customer id",
     *   @OA\Parameter(name="customerId", in="path", description="Customer id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('customer/{customerId}', 'Customer\CustomerController@getCustomer')->where('customerId', '[0-9]+')->name('customers.get');


    /**
     * @OA\Patch(path="/admin/customer/{customerId}",
     *   tags={"Customer"},
     *   summary="Update customer status",
     *   description="Update customer status ACTIVE / BLOCKED",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/CustomerStatusUpdateInputData"))),
     *   @OA\Parameter(name="customerId", in="path", description="Customer Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    Route::patch('customer/{customerId}', 'Customer\CustomerController@changeStatus')->where('customerId', '[0-9]+')->name('customers.change_status');

    /**
     * @OA\Delete(path="/admin/customer/{customerId}",
     *   tags={"Customer"},
     *   summary="Delete single customer",
     *   description="Delete single customer by customer id",
     *   @OA\Parameter(name="customerId", in="path", description="Customer id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('customer/{customerId}', 'Customer\CustomerController@delete')->where('customerId', '[0-9]+')->name('customers.delete');
