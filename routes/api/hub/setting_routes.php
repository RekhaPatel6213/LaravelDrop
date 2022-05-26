<?php

    /**
     * @OA\get(path="/hub/setting/delivery",
     *   tags={"Hub Settings >> Delivery Minimum && Taxes"},
     *   summary="Get delivery costs",
     *   description="Get delivery costs",
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/DeliveryCostsSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/DelCostsList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/delivery', 'TaxesCostsSettingController@getDeliveryCosts');

    /**
     * @OA\Patch(path="/hub/setting/delivery",
     *   tags={"Hub Settings >> Delivery Minimum && Taxes"},
     *   summary="Update delivery costs",
     *   description="Update delivery costs",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/DeliveryCostsUpdateData"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DeliveryCostsUpdateData")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/delivery', 'TaxesCostsSettingController@updateTaxesDeliveryCosts');

    /**
     * @OA\get(path="/hub/setting/taxes",
     *   tags={"Hub Settings >> Delivery Minimum && Taxes"},
     *   summary="Get taxes",
     *   description="Get taxes",
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/TaxSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/TaxCostsList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/taxes', 'TaxesCostsSettingController@getTaxes');


    /**
     * @OA\Patch(path="/hub/setting/taxes",
     *   tags={"Hub Settings >> Delivery Minimum && Taxes"},
     *   summary="Update taxes",
     *   description="Update taxes",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/TaxUpdateData"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/TaxUpdateData")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/taxes', 'TaxesCostsSettingController@updateTaxesDeliveryCosts');


    /**
     * @OA\Get(path="/hub/setting/purchase/{state}",
     *   tags={"Hub Settings"},
     *   summary="Get daily purchase limit",
     *   description="Get daily purchase limit",
     *   @OA\Parameter(name="state", in="path", description="state", required=true, example="KY"),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/PurchaseLimitList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/purchase/{state}', 'SettingsController@getPurchaseLimit')->where('state', '[A-Za-z_]+');

    /**
     * @OA\Patch(path="/hub/setting/purchase",
     *   tags={"Hub Settings"},
     *   summary="Update daily purchase limit",
     *   description="Update daily purchase limit",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PurchaseLimitUpdateData"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/PurchaseLimitList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/purchase', 'SettingsController@updatePurchaseLimit');


    /**
     * @OA\get(path="/hub/setting/shop-timings",
     *   tags={"Hub Settings"},
     *   summary="Get shop information",
     *   description="Get shop information",
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/shop-timings', 'DispensaryHourSetController@getShopTimings');

    /**
     * @OA\Patch(path="/hub/setting/shop-timings",
     *   tags={"Hub Settings"},
     *   summary="Update shop information",
     *   description="Update shop information",
     *   requestBody={"$ref": "#/components/requestBodies/HubDispensaryTimingsAddUpdateRequest"},
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/shop-timings', 'DispensaryHourSetController@updateShopTimings');


    /**
     * @OA\Delete(path="/hub/setting/shop-timings/{hourSetId}",
     *   tags={"Hub Settings"},
     *   summary="Delete hour set",
     *   description="Delete hour set by providing hour set id",
     *   @OA\Parameter(name="hourSetId", in="path", description="Hour set id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('setting/shop-timings/{hourSetId}', 'DispensaryHourSetController@deleteShopTimings')->where('hourSetId', '[0-9]+')->name('shop_timings.delete');

    /**
     * @OA\get(path="/hub/setting/phone",
     *   tags={"Hub Settings"},
     *   summary="Get Phone numbers",
     *   description="Get Phone numbers",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SinglePhoneNumbersSchema")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/phone', 'SettingsController@getPhoneNumbers');

    /**
     * @OA\put(path="/hub/setting/phone",
     *   tags={"Hub Settings"},
     *   summary="Update Phone numbers",
     *   description="Update Phone numbers",
     *   requestBody={"$ref": "#/components/requestBodies/HubDispensaryPhoneNumbersRequest"},
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SinglePhoneNumbersSchema")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('setting/phone', 'SettingsController@updatePhoneNumbers');


    /**
     * @OA\get(path="/hub/setting/drop-offs",
     *   tags={"Hub Settings"},
     *   summary="Get Drop Off options",
     *   description="Get Drop Off options",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DropOffOptionsListRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/drop-offs', 'DropOffOptionController@getDropOffOptions');


    /**
     * @OA\patch(path="/hub/setting/drop-offs/{dropOptionId}",
     *   tags={"Hub Settings"},
     *   summary="Update Drop Off options",
     *   description="Update Drop Off options",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/DropOffOptions"))),
     *   @OA\Parameter(name="dropOptionId", in="path", description="Drop Off Option Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DropOffOptionsListAllRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/drop-offs/{dropOptionId}', 'DropOffOptionController@saveDropOffOptions')->where('dropOptionId', '[0-9]+');

    /**
     * @OA\post(path="/hub/setting/drop-offs",
     *   tags={"Hub Settings"},
     *   summary="Add Drop Off options",
     *   description="Add Drop Off options",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/DropOffOptions"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DropOffOptionsListAllRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/drop-offs', 'DropOffOptionController@saveDropOffOptions');


    /**
     * @OA\get(path="/hub/setting/payment-options",
     *   tags={"Hub Settings"},
     *   summary="Get payment options",
     *   description="Get payment options",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/PaymentMethodsListRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/payment-options', 'DispensaryPaymentMethodController@getPaymentMethods');

    /**
     * @OA\post(path="/hub/setting/payment-options",
     *   tags={"Hub Settings"},
     *   summary="Add payment options",
     *   description="Add payment options",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PaymentMethods"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/PaymentMethodsListSingle")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/payment-options', 'DispensaryPaymentMethodController@addPaymentMethod');


    /**
     * @OA\put(path="/hub/setting/payment-options",
     *   tags={"Hub Settings"},
     *   summary="Update payment options",
     *   description="Update payment options",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PaymentMethodsUpdate"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/PaymentMethodsListSingle")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('setting/payment-options', 'DispensaryPaymentMethodController@updatePaymentMethods');

    /**
     * @OA\get(path="/hub/setting/order-fees",
     *   tags={"Hub Settings"},
     *   summary="Get custom order fees",
     *   description="Get custom order fees",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/CustomFeesUpdate")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/order-fees', 'SettingsController@getCustomOrderFees');

    /**
     * @OA\post(path="/hub/setting/order-fees",
     *   tags={"Hub Settings"},
     *   summary="Add custom order fees",
     *   description="Add custom order fees",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/CustomFees"))),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/order-fees', 'SettingsController@addCustomOrderFees');


    /**
     * @OA\put(path="/hub/setting/order-fees",
     *   tags={"Hub Settings"},
     *   summary="Update custom order fees",
     *   description="Update custom order fees",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/CustomFeesUpdate"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/CustomFeesUpdate")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('setting/order-fees', 'SettingsController@updateCustomOrderFees');

    /**
     * @OA\Delete(path="/hub/setting/order-fees/{orderFeeId}",
     *   tags={"Hub Settings"},
     *   summary="Remove custom order fees",
     *   description="Remove custom order fees",
     *   @OA\Parameter(name="orderFeeId", in="path", description="order fee id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DeleteResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('setting/order-fees/{orderFeeId}', 'SettingsController@deleteCustomOrderFees')->where('orderFeeId', '[0-9]+')->name('order_fees.delete');


    /**
     * @OA\get(path="/hub/setting/home-messages",
     *   tags={"Hub Settings"},
     *   summary="Get Home page display messages",
     *   description="Get Home page display messages",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/HomeMessagesRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/home-messages', 'SettingsController@getHomeMessages');


    /**
     * @OA\post(path="/hub/setting/home-messages",
     *   tags={"Hub Settings"},
     *   summary="Add Home page display message",
     *   description="Add Home page display message",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/MessageBoxInputData"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/MessageBoxInputData")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/home-messages', 'SettingsController@saveHomeMessage');


    /**
     * @OA\post(path="/hub/setting/home-messages/reorder",
     *   tags={"Hub Settings"},
     *   summary="Reorder messages",
     *   description="Reorder messages",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/MessageBoxReorder"))),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/HomeMessagesRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/home-messages/reorder', 'SettingsController@reorderMessages')->name('messages.reorder');

    /**
     * @OA\patch(path="/hub/setting/home-messages/{messageId}",
     *   tags={"Hub Settings"},
     *   summary="Update Home page display message",
     *   description="Update Home page display message",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/MessageBoxInputData"))),
     *   @OA\Parameter(name="messageId", in="path", description="Message Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/MessageBoxInputData")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/home-messages/{messageId}', 'SettingsController@saveHomeMessage')->where('messageId', '[0-9]+')->name('messages.save');


    /**
     * @OA\delete(path="/hub/setting/home-messages/{messageId}",
     *   tags={"Hub Settings"},
     *   summary="Delete Home page display message",
     *   description="Delete Home page display message",
     *   @OA\Parameter(name="messageId", in="path", description="Message Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/DeleteResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('setting/home-messages/{messageId}', 'SettingsController@deleteMessage')->where('messageId', '[0-9]+')->name('messages.delete');


    /**
     * @OA\Get(path="/hub/setting/notification",
     *   tags={"Hub Settings"},
     *   summary="Notification Listing.",
     *   description="List all notification data",
     *   @OA\Parameter(
     *         description="Search By Title - Enter keyword to search title",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/NotificationSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/NotificationList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('setting/notification', 'SettingsController@listNotification');

    /**
     * @OA\Post(path="/hub/setting/notification",
     *   tags={"Hub Settings"},
     *   summary="Add a new notification",
     *   description="Add a new notification",
     *   operationId="store",
     *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/NotificationInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/notification', 'SettingsController@addNotification');

    /**
     * @OA\Delete(path="/hub/setting/notification/{notificationId}",
     *   tags={"Hub Settings"},
     *   summary="Delete single notification",
     *   description="Delete single notification by notification id",
     *   @OA\Parameter(name="notificationId", in="path", description="Notification id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('setting/notification/{notificationId}', 'SettingsController@deleteNotification')->where('notificationId', '[0-9]+');

    /**
     * @OA\Get(path="/hub/setting/faq",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="FAQ Listing.",
     *   description="List All Faq Data",
     *   @OA\Parameter(
     *         description="Search By Question - Enter keyword to search",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubFaqList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/faq', 'SettingsController@listFaq');

    /**
     * @OA\POST(
     *      tags = {"Hub Settings >> Faq && Legal"},
     *      path = "/hub/setting/faq",
     *      summary = "Add Hub FAQ",
     *      description = "API for creating new Hub Setting FAQ",
     *      operationId="createFaq",
     *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/HubFaqInputData"))),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('setting/faq', 'SettingsController@addFaq');

    /**
     * @OA\Get(path="/hub/setting/faq/{faqId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Get single Hub FAQ",
     *   description="Get single Hub FAQ Details",
     *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1"),
     *   @OA\Response(response=200, description="Get Single FAQ Record",@OA\JsonContent(ref="#/components/schemas/HubFaq")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/faq/{faqId}', 'SettingsController@getFaq')->where('faqId', '[0-9]+');

    /**
     * @OA\Put(path="/hub/setting/faq/{faqId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Hub FAQ Update Form",
     *   description="Hub Update FAQ",
     *   requestBody={"$ref": "#/components/requestBodies/HubFaqRequest"},
     *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubFaq")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('setting/faq/{faqId}', 'SettingsController@updateFaq')->where('faqId', '[0-9]+');

    /**
     * @OA\Patch(path="/hub/setting/faq/{faqId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Hub FAQ Update Form",
     *   description="Hub Update FAQ",
     *   requestBody={"$ref": "#/components/requestBodies/HubFaqRequest"},
     *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubFaq")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/faq/{faqId}', 'SettingsController@updateFaq')->where('faqId', '[0-9]+');

    /**
     * @OA\Delete(path="/hub/setting/faq/{faqId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Delete Hub FAQ",
     *   description="Delete Hub FAQ By ID",
     *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('setting/faq/{faqId}', 'SettingsController@deleteFaq')->where('faqId', '[0-9]+');

    /**
     * @OA\Get(path="/hub/setting/legal/{legalId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Get Single Hub Legal",
     *   description="Get single hub legal term & privacy Details",
     *   @OA\Parameter(name="legalId", in="path", description="Legal ID", required=true, example="1"),
     *   @OA\Response(response=200, description="Get Single Legal Record",@OA\JsonContent(ref="#/components/schemas/HubPage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Legal Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/legal/{legalId}', 'SettingsController@getLegal')->where('legalId', '[0-9]+');

    /**
     * @OA\Put(path="/hub/setting/legal/{legalId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Hub Legal Update Form",
     *   description="Hub Update Legal Term & Privacy",
     *   requestBody={"$ref": "#/components/requestBodies/HubPageRequest"},
     *   @OA\Parameter(name="legalId", in="path", description="Legal ID", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubPage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('setting/legal/{legalId}', 'SettingsController@updateLegal')->where('legalId', '[0-9]+');

    /**
     * @OA\Patch(path="/hub/setting/legal/{legalId}",
     *   tags={"Hub Settings >> Faq && Legal"},
     *   summary="Hub Legal Update Form",
     *   description="Hub Update Legal Term & Privacy",
     *   requestBody={"$ref": "#/components/requestBodies/HubPageRequest"},
     *   @OA\Parameter(name="legalId", in="path", description="Legal ID", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/HubPage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('setting/legal/{legalId}', 'SettingsController@updatelegal')->where('faqId', '[0-9]+');

    /**
     * @OA\Get(path="/hub/setting",
     *   tags={"Hub Settings"},
     *   summary="Get All Dispensary Hub Setting Details",
     *   description="Get All Dispensary Hub Setting Details",
     *   @OA\Response(response=200, description="Get All Dispensary Hub Setting Details", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting', 'SettingsController@getSetting');

    /**
     * @OA\Get(path="/hub/setting/{type}",
     *   tags={"Hub Settings"},
     *   summary="Get single Dispensary Hub Setting Details",
     *   description="Get single Dispensary Hub Setting Details",
     *   @OA\Parameter(in="path", name="type", @OA\Schema(ref="#/components/schemas/SettingType")),
     *   @OA\Response(response=200, description="Get All Dispensary Hub Setting Details", @OA\JsonContent(ref="#/components/schemas/SuccessResponseObject")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('setting/{type}', 'SettingsController@getSetting')->where('type', '[A-Za-z_]+');

    /**
     * @OA\Put(path="/hub/setting/{type}",
     *   tags={"Hub Settings"},
     *   summary="Dispensary Hub Setting Detail Update Form",
     *   description="Update Dispensary Hub Setting Detail",
     *   @OA\Parameter(in="path", name="type", @OA\Schema(ref="#/components/schemas/SettingType")),
     *   requestBody={"$ref": "#/components/requestBodies/HubSettingUpdate"},
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponseObject")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('setting/{type}', 'SettingsController@updateSetting')->where('type', '[A-Za-z_]+');
