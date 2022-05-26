<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 *  @OA\Schema(schema="SettingType", type="string", enum={
 *     "email_notifications",
 *     "sms_notifications",
 *     "app_settings",
 *     "dispatch_settings",
 *     "inventory_settings",
 *     "website_settings",
 *     "customer_verification",
 *     "new_drop_notification",
 *     "estimated_delivery",
 *     "dropkit",
 *     "payment_options",
 *     "order_fees",
 *     "taxes",
 *     "branding_options"
 * })
 *
 *  @OA\Schema(schema="HubSetting",
 *      @OA\Property(property="new_order", type="boolean", description="An order is submitted"),
 *      @OA\Property(property="canceled_order", type="boolean", description="An order is canceled"),
 *      @OA\Property(property="weekly_summary", type="boolean", description="Weekly summary"),
 *      @OA\Property(property="order_receipt", type="boolean", description="Customer Receipt")
 *  )
 *
 *  @OA\RequestBody(
 *     request="HubSettingUpdate1",
 *     description="Dispensary Hub Settings Data Request body",
 *     required=true,
 *     @OA\JsonContent(ref="#/components/schemas/HubSetting")
 *  )
 *
 *  @OA\RequestBody(
 *     request="HubSettingUpdate",
 *     description="Dispensary Hub Settings Data Request body",
 *     required=true,
 *     @OA\JsonContent(
 *      oneOf={
 *          @OA\Schema(ref="#/components/schemas/EmailNotifications"),
 *          @OA\Schema(ref="#/components/schemas/SMSNotifications"),
 *          @OA\Schema(ref="#/components/schemas/AppSettings"),
 *          @OA\Schema(ref="#/components/schemas/DispatchSettings"),
 *          @OA\Schema(ref="#/components/schemas/InventorySettings"),
 *          @OA\Schema(ref="#/components/schemas/WebsiteSettings"),
 *          @OA\Schema(ref="#/components/schemas/CustomerVerification"),
 *          @OA\Schema(ref="#/components/schemas/NewDropNotification"),
 *          @OA\Schema(ref="#/components/schemas/EstimatedDelivery"),
 *          @OA\Schema(ref="#/components/schemas/Dropkit"),
 *          @OA\Schema(ref="#/components/schemas/PaymentOptions"),
 *          @OA\Schema(ref="#/components/schemas/OrderFees"),
 *          @OA\Schema(ref="#/components/schemas/Taxes"),
 *          @OA\Schema(ref="#/components/schemas/BrandingOptions"),
 *      },
 *      examples={
 *          @OA\Examples(example="EmailNotifications", summary="Hub Settings of Email Notifications",
 *          value={
 *              "new_order" : true,
 *              "canceled_order" : true,
 *              "weekly_summary" : true,
 *              "order_receipt" : true
 *          }),
 *
 *          @OA\Examples(example="SMSNotifications", summary="Hub Settings of SMS Notifications",
 *          value={
 *              "new_order" : true,
 *              "canceled_order" : true,
 *              "customer_order" : true
 *          }),
 *
 *          @OA\Examples(example="AppSettings", summary="Hub >> App Settings",
 *          value={
 *              "add_product_notes" : true
 *          }),
 *
 *          @OA\Examples(example="DispatchSettings", summary="Hub >> Dispatch Settings",
 *          value={
 *              "bypass_order_minimum" : true,
 *          }),
 *
 *          @OA\Examples(example="InventorySettings", summary="Hub >> Inventory Settings",
 *          value={
 *              "min_stock_alert" : true,
 *              "min_gram_qty" : 10,
 *              "min_unit_qty" : 100,
 *          }),
 *
 *          @OA\Examples(example="WebsiteSettings", summary="Hub >> Website",
 *          value={
 *              "homepage_title" : "Homepage Title",
 *              "homepage_meta" : "Homepage Meta",
 *              "seo" : "seo text",
 *          }),
 *
 *          @OA\Examples(example="CustomerVerification", summary="Hub >> Customer Verification",
 *          value={
 *              "recreational" : {
 *                  "age" : 17,
 *                  "type_enabled" : true,
 *                  "selfie_upload" : true,
 *              },
 *              "medical" : {
 *                  "age" : 16,
 *                  "medical_rec_upload" : true,
 *              },
 *          }),
 *          @OA\Examples(example="NewDropNotification", summary="Hub >> Delivery Phone Number >> New Drop Notification",
 *              value={
 *                  "new_drop_notification" : true
 *          }),
 *
 *          @OA\Examples(example="EstimatedDelivery", summary="Hub >> Delivery Setting >> Estimated Delivery",
 *              value={
 *                  "estimated_from" : 1,
 *                  "estimated_to" : 120,
 *                  "scheduled_delivery_time_bracket" : 15,
 *                  "scheduled_delivery_day_advance" : 7,
 *          }),
 *
 *          @OA\Examples(example="Dropkit", summary="Hub >> Dropkit",
 *              value={
 *              "dropkit" : false
 *          }),
 *
 *          @OA\Examples(example="PaymentOptions", summary="Hub >> Delivery Setting >> Payment Options",
 *              value={
 *                  "debit_card_fee" : 10
 *          }),
 *
 *          @OA\Examples(example="OrderFees", summary="Hub >> Delivery Setting >> Order Fees",
 *              value={
 *                  "customer_pays_service_fee" : true,
 *                  "service_fee_amount" : 0.99,
 *                  "additional_service_fee_amount" : 1,
 *          }),
 *
 *          @OA\Examples(example="Taxes", summary="Hub >> Taxes",
 *              value={
 *                  "state" : "AK",
 *                  "sale_and_use" : true,
 *                  "excise" : true,
 *                  "cannabis" : false,
 *          }),
 *
 *          @OA\Examples(example="BrandingOptions", summary="Hub >> Settings >> Branding",
 *              value={
 *                  "enable_phone_call" : true,
 *                  "enable_text_button" : true,
 *                  "enable_email_button" : true,
 *          }),
 *      }
 *    )
 *  )
 *
 *  @OA\Schema(schema="EmailNotifications", type="object",
 *     @OA\Property(property="new_order", type="boolean", description="An order is submitted"),
 *      @OA\Property(property="canceled_order", type="boolean", description="An order is canceled"),
 *      @OA\Property(property="weekly_summary", type="boolean", description="Weekly summary"),
 *      @OA\Property(property="order_receipt", type="boolean", description="Customer Receipt")
 *  )
 *
 *  @OA\Schema(schema="SMSNotifications", type="object",
 *     @OA\Property(property="new_order", type="boolean", description="An order is submitted"),
 *      @OA\Property(property="canceled_order", type="boolean", description="An order is canceled"),
 *      @OA\Property(property="customer_order", type="boolean", description="Weekly summary")
 *  )
 *
 *  @OA\Schema(schema="AppSettings", type="object",
 *     @OA\Property(property="add_product_notes", type="boolean", description="Allow customer to add notes to product"),
 *  )
 *
 *  @OA\Schema(schema="DispatchSettings", type="object",
 *     @OA\Property(property="bypass_order_minimum", type="boolean", description="Allow dispatch to bypass order minimum"),
 *  )
 *
 * @OA\Schema(schema="InventorySettings", type="object",
 *     @OA\Property(property="min_stock_alert", type="boolean", description="Show 'ONLY A FEW LEFT' Banner"),
 *     @OA\Property(property="min_gram_qty", type="string", description="'Minimum Gram Quantity"),
 *     @OA\Property(property="min_unit_qty", type="string", description="'Minimum Unit Quantity"),
 *  )
 *
 * @OA\Schema(schema="WebsiteSettings", type="object",
 *     @OA\Property(property="homepage_title", type="string", description="Homepage Title"),
 *     @OA\Property(property="homepage_meta", type="string", description="Homepage Meta"),
 *     @OA\Property(property="seo", type="string", description="SEO location")
 *  )
 *
 * @OA\Schema(schema="CustomerVerification", type="object",
 *     @OA\Property(property="recreational", type="object", ref="#/components/schemas/RecreationalSchema"),
 *     @OA\Property(property="medical", type="object", ref="#/components/schemas/MedicalSchema")
 *  )
 *
 * @OA\Schema(schema="RecreationalSchema", type="object",
 *     @OA\Property(property="age", type="integer", description="Age"),
 *     @OA\Property(property="type_enabled", type="boolean", description="Recreational Checkbox Checked"),
 *     @OA\Property(property="id_scan", type="boolean", description="Id scan"),
 *     @OA\Property(property="id_scan_upload", type="boolean", description="Id scan upload image"),
 *     @OA\Property(property="selfie", type="boolean", description="Selfie"),
 *     @OA\Property(property="selfie_upload", type="boolean", description="Selfie Upload Image")
 *  )
 *
 * @OA\Schema(schema="MedicalSchema", type="object",
 *     @OA\Property(property="age", type="integer", description="Age"),
 *     @OA\Property(property="type_enabled", type="boolean", description="Recreational Checkbox Checked"),
 *     @OA\Property(property="id_scan", type="boolean", description="Id scan"),
 *     @OA\Property(property="id_scan_upload", type="boolean", description="Id scan upload image"),
 *     @OA\Property(property="selfie", type="boolean", description="Selfie"),
 *     @OA\Property(property="selfie_upload", type="boolean", description="Selfie Upload Image"),
 *     @OA\Property(property="medical_rec", type="boolean", description="Medical Rec"),
 *     @OA\Property(property="medical_rec_upload", type="boolean", description="Medical Rec Upload Image"),
 *  )
 *
 * @OA\Schema(schema="NewDropNotification", type="object",
 *     @OA\Property(property="new_drop_notification", type="boolean", description="New drop notification"),
 *  )
 *
 * @OA\Schema(schema="EstimatedDelivery", type="object",
 *     @OA\Property(property="estimated_from", type="text", description="Estimate FROM time"),
 *     @OA\Property(property="estimated_to", type="text", description="Estimate TO time"),
 *     @OA\Property(property="scheduled_delivery_time_bracket", type="text", description="Scheduled delivery time bracket"),
 *     @OA\Property(property="scheduled_delivery_day_advance", type="text", description="Scheduled delivery day advance"),
 *  )
 *
 * @OA\Schema(schema="Dropkit", type="object",
 *     @OA\Property(property="dropkit", type="boolean", description="Dropkit Feature Setting")
 * )
 * @OA\Schema(schema="PaymentOptions", type="object",
 *     @OA\Property(property="debit_card_fee", type="number", description="Debit Card Fee")
 *  )
 *
 * @OA\Schema(schema="OrderFees", type="object",
 *     @OA\Property(property="customer_pays_service_fee", type="boolean"),
 *     @OA\Property(property="service_fee_amount", type="integer"),
 *     @OA\Property(property="additional_service_fee_amount", type="integer"),
 *  )
 *
 * @OA\Schema(schema="Taxes", type="object",
 *     @OA\Property(property="state", type="text"),
 *     @OA\Property(property="sale_and_use", type="boolean"),
 *     @OA\Property(property="excise", type="boolean"),
 *     @OA\Property(property="cannabis", type="boolean"),
 *  )
 *
 * @OA\Schema(schema="BrandingOptions", type="object",
 *     @OA\Property(property="enable_phone_call", type="boolean"),
 *     @OA\Property(property="enable_text_button", type="boolean"),
 *     @OA\Property(property="enable_email_button", type="boolean"),
 *  )
 */
class DispensarySettings extends Settings
{
    public array $email_notifications;
    public array $sms_notifications;
    public array $app_settings;
    public array $dispatch_settings;
    public array $inventory_settings;
    public array $website_settings;
    public array $customer_verification;
    public array $new_drop_notification;
    public array $estimated_delivery;
    public bool $dropkit;
    public array $payment_options;
    public array $order_fees;
    public array $taxes;
    public array $branding_options;

    const WEBSITE_SETTINGS = 'website_settings';
    const ESTIMATED_DELIVERY = 'estimated_delivery';
    const CUSTOMER_VERIFY = 'customer_verification';
    const PAYMENT_OPTIONS = 'payment_options';
    const ORDER_FEES = 'order_fees';

    public static function group(): string
    {
        return 'hub_setting';
    }
}