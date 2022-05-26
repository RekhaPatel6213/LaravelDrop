<?php
    /**
     * @OA\Get(
     *    tags = {"Billing Invoice"},
     *    path = "/hub/subscription",
     *    summary = "Get Dispensary Subscription Details",
     *    description = "API for Get Dispensary Subscription Details",
     *    operationId="SubscriptionDetails",
     *    @OA\Response(response=200, description="success response"),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('subscription', 'CreditCardController@subscription')->name('subscription.detail');

    /**
     * @OA\Get(
     *   tags={"Billing Invoice"},
     *   path="/hub/invoice",
     *   summary="Get All invoices",
     *   description="List All invoices",
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/AdminSortsOn")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/InvoiceList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('invoice', 'CreditCardController@invoiceList')->where('dispensaryId', '[0-9]+')->name('hub.invoice.list');

    /**
     * @OA\Get(
     *   tags={"Billing Invoice"},
     *   path="/hub/invoice/{invoiceId}",
     *   summary="Get invoice details",
     *   description="Get invoice details using Invoice Id",
     *   @OA\Parameter(name="invoiceId", in="path", description="Invoice ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/InvoiceDetails")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('invoice/{invoiceId}', 'CreditCardController@invoiceDetail')->where('invoiceId', '[0-9]+')->name('hub.invoice.detail');
    /**
     * @OA\Get(
     *   tags={"Billing Invoice"},
     *   path="/hub/credit-cardt",
     *   summary="Get All Credit Card list",
     *   description="List All Credit Cards",
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/CreditCardSortsOn")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/CreditCardList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('credit-cardt', 'CreditCardController@list')->where('dispensaryId', '[0-9]+')->name('card.list');

    /**
     * @OA\Post(
     *   tags = {"Billing Invoice"},
     *   path = "/hub/credit-card",
     *   summary = "Add Credit Card info",
     *   description = "API for Add Credit Card info",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/CreditCardData"))),
     *   @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('credit-card', 'CreditCardController@store')->name('card.create');

    /**
     * @OA\Post(
     *   tags={"Billing Invoice"},
     *   path="/hub/credit-card/{creditCardId}",
     *   summary="Set Default Credit Card",
     *   description="Set Default Credit Card",
     *   @OA\Parameter(name="creditCardId", in="path", description="Credit Card ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('credit-card/{creditCardId}', 'CreditCardController@setDefault')->where('creditCardId', '[0-9]+')->name('card.default');

    /**
     * @OA\Delete(
     *   tags={"Billing Invoice"},
     *   path="/hub/credit-card/{creditCardId}",
     *   summary="Delete Credit Card",
     *   description="Delete Credit Card",
     *   @OA\Parameter(name="creditCardId", in="path", description="Credit Card ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('credit-card/{creditCardId}', 'CreditCardController@delete')->where('creditCardId', '[0-9]+')->name('card.delete');