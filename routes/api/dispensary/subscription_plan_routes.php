<?php 
    /**
     * @OA\Get(
     *   tags={"Dispensary Stripe Subscription"},
     *   path="/admin/stripe-subscribe",
     *   summary="Stripe Subscription Price Listing.",
     *   description="List All Stripe Subscription Prices",
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SubscriptionPriceList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('/stripe-subscribe', 'Dispensary\SubscriptionPriceController@list');

    /**
     * @OA\Post(
     *      tags = {"Dispensary Stripe Subscription"},
     *      path = "/admin/stripe-subscribe",
     *      summary = "Create New Stripe Subscription Price",
     *      description = "API for creating new Stripe Subscription Price",
     *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/SubscriptionPriceData"))),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('/stripe-subscribe', 'Dispensary\SubscriptionPriceController@create');

    /**
     * @OA\Post(
     *      tags = {"Dispensary Stripe Subscription"},
     *      path = "/admin/stripe-balance-add",
     *      summary = "Add Balance in stripe dispensary account",
     *      description = "API for Add Balance in stripe dispensary account",
     *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/StripeBalanceAdd"))),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('/stripe-balance-add', 'Dispensary\SubscriptionPriceController@stripeBalanceAdd');

    /**
     * @OA\Get(
     *   tags={"Dispensary Stripe Subscription"},
     *   path="/admin/stripe-balance-list/{dispensaryId}",
     *   summary="Get All customer balance transaction from Stripe",
     *   description="List All Stripe ustomer balance transactions",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/StripeBalanceTransactionList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('/stripe-balance-list/{dispensaryId}', 'Dispensary\SubscriptionPriceController@stripeCustomerBalanceTransaction')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Get(
     *   tags={"Dispensary Stripe Subscription"},
     *   path="/admin/invoice-list/{dispensaryId}",
     *   summary="Get All invoices",
     *   description="List All invoices",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/InvoiceList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('/invoice-list/{dispensaryId}', 'Dispensary\SubscriptionPriceController@invoiceList')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Post(
     *   tags={"Dispensary Stripe Subscription"},
     *   path="/admin/invoice-detail/{invoiceId}",
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
    Route::post('/invoice-detail/{invoiceId}', 'Dispensary\SubscriptionPriceController@invoiceDetail')->where('invoiceId', '[0-9]+');

    /**
     * @OA\Get(
     *   tags={"Testing APIs"},
     *   path="/admin/stripe-invoice-list/{dispensaryId}",
     *   summary="Get All invoices from Stripe",
     *   description="List All Stripe invoices",
     *   @OA\Parameter(name="dispensaryId", in="path", description="Dispensary ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/StripeInvoiceList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('/stripe-invoice-list/{dispensaryId}', 'Dispensary\SubscriptionPriceController@stripeInvoiceList')->where('dispensaryId', '[0-9]+');

    /**
     * @OA\Post(
     *   tags={"Testing APIs"},
     *   path="/admin/stripe-invoice-detail",
     *   summary="Get invoices from Stripe using Invoice Id",
     *   description="Get invoices from Stripe using Invoice Id",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/StripeInvoiceDetail"))),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/StripeInvoiceList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('/stripe-invoice-detail', 'Dispensary\SubscriptionPriceController@stripeInvoiceDetail');