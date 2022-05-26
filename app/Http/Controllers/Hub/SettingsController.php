<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dispensary\DispensaryCustomFeeRequest;
use App\Http\Requests\Admin\Dispensary\PurchaseLimitUpdateRequest;
use App\Http\Requests\Hub\FaqCreateRequest;
use App\Http\Requests\Hub\FaqRequest;
use App\Http\Requests\Hub\MessageBoxRequest;
use App\Http\Requests\Hub\NotificationRequest;
use App\Http\Requests\Hub\PageRequest;
use App\Http\Requests\Hub\DispensaryUserRequest;
use App\Http\Requests\Hub\SettingsRequest;
use App\Services\Admin\Dispensary\DispensaryCustomFeeService;
use App\Services\Admin\Dispensary\PurchaseLimitService;
use App\Services\Admin\SettingsService;
use App\Services\Hub\FaqService;
use App\Services\Hub\MessageBoxService;
use App\Services\Hub\NotificationService;
use App\Services\Hub\PageService;
use App\Services\Territory\TerritoryService;
use App\Services\Hub\DispensaryUserService;
use App\Settings\DispensarySettings;
use App\Transformers\Admin\Dispensary\DispensaryCustomFeeTransformer;
use App\Transformers\Hub\Dispensary\PurchaseLimitTransformer;
use App\Transformers\Hub\FaqTransformer;
use App\Transformers\Hub\MessageBoxTransformer;
use App\Transformers\Hub\NotificationTransformer;
use App\Transformers\Hub\PageTransformer;
use App\Transformers\Hub\DispensaryUserTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    protected $service;
    protected $costsSettingService;
    protected $limitService;
    protected $territoryService;
    protected $feeService;
    protected $messageBoxService;
    protected $faqService;
    protected $pageService;
    protected $dispenUserService;
    protected $notificationService;
    protected $feeTransformer;
    protected $limitTransformer;
    protected $messageBoxTransformer;
    protected $faqTransformer;
    protected $pageTransformer;
    protected $notificatTransformer;
    protected $dispenUserTransformer;

    public function __construct(
        SettingsService $service,
        PurchaseLimitService $limitService,
        TerritoryService $territoryService,
        DispensaryCustomFeeService $feeService,
        MessageBoxService $messageBoxService,
        FaqService $faqService,
        PageService $pageService,
        NotificationService $notificationService,
        DispensaryUserService $dispenUserService,
        DispensaryCustomFeeTransformer $feeTransformer,
        PurchaseLimitTransformer $limitTransformer,
        MessageBoxTransformer $messageBoxTransformer,
        FaqTransformer $faqTransformer,
        PageTransformer $pageTransformer,
        NotificationTransformer $notificatTransformer,
        DispensaryUserTransformer $dispenUserTransformer
    ) {
        $this->service = $service;
        $this->limitService = $limitService;
        $this->territoryService = $territoryService;
        $this->feeService = $feeService;
        $this->messageBoxService = $messageBoxService;
        $this->faqService = $faqService;
        $this->pageService = $pageService;
        $this->notificationService = $notificationService;
        $this->dispenUserService = $dispenUserService;
        $this->limitTransformer = $limitTransformer;
        $this->feeTransformer = $feeTransformer;
        $this->messageBoxTransformer = $messageBoxTransformer;
        $this->faqTransformer = $faqTransformer;
        $this->pageTransformer = $pageTransformer;
        $this->notificationTransformer = $notificatTransformer;
        $this->dispenUserTransformer = $dispenUserTransformer;
    }

    public function getSetting(string $type = null, DispensarySettings $setting)
    {
        try {
            $settingData = $this->service->getHubSetting($setting, $type);

            return $this->returnJsonResponse($settingData);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updateSetting(string $type, SettingsRequest $request, DispensarySettings $setting)
    {
        try {
            $setting = $this->service->saveHubSetting($setting, $type, $request->all());

            return $this->returnJsonResponse($setting);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }
    //TODO
    public function getPurchaseLimit(string $state)
    {
        try {
            $purchaseLimit = $this->limitService->getPurchaseLimit($state);
            if (!$purchaseLimit['success']) {
                return response()->json(['message' => $purchaseLimit['message']]);
            }

            return $this->item($purchaseLimit['data'], $this->limitTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updatePurchaseLimit(PurchaseLimitUpdateRequest $request)
    {
        try {
            $purchaseLimit = $this->limitService->saveOrUpdate($request->all());

            return $this->item($purchaseLimit, $this->limitTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getPhoneNumbers()
    {
        try {
            $phones = $this->territoryService->getPhoneNumbers(true);
            return $this->returnJsonResponse($phones);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updatePhoneNumbers(Request $request)
    {
        try {
            $phones = $this->territoryService->updatePhoneNumbers($request->all());

            return $this->returnJsonResponse($phones);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getCustomOrderFees()
    {
        try {
            $customFees = $this->feeService->getAllCustomFees();
            return $this->collection($customFees, $this->feeTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function addCustomOrderFees(Request $request)
    {
        try {
            $orderFees = $this->feeService->store($request->all());

            return $this->item($orderFees, $this->feeTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function updateCustomOrderFees(DispensaryCustomFeeRequest $request)
    {
        try {
            $customFees = $this->feeService->updateCustomFees($request->all());

            return $this->collection($customFees, $this->feeTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function deleteCustomOrderFees(DispensaryCustomFeeRequest $request)
    {
        try {
            $orderFeeId = $request->route('orderFeeId');
            $this->feeService->delete($orderFeeId);

            return $this->returnJsonResponse(['message' => __('message.order_fee_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }


    public function getHomeMessages()
    {
        try {
            $messages = $this->messageBoxService->list();

            return $this->collection($messages, $this->messageBoxTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function saveHomeMessage(MessageBoxRequest $request)
    {
        try {
            $messageId = $request->route('messageId');
            $messages = $this->messageBoxService->store($request->all(), $messageId);

            return $this->item($messages, $this->messageBoxTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function reorderMessages(MessageBoxRequest $request)
    {
        try {
            $messages = $this->messageBoxService->reorderMessages($request->all());

            return $this->collection($messages, $this->messageBoxTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function deleteMessage(MessageBoxRequest $request)
    {
        try {
            $messageId = $request->route('messageId');
            $this->messageBoxService->delete($messageId);
            return $this->returnJsonResponse(['message' => __('message.message_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
        $request->merge(['message_id' => $messageId]);
        $request->validate(['message_id' => 'required|exists:message_boxes,id']);

        return $messageId;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listNotification(Request $request)
    {
        try {
            $data = $this->notificationService->getListing($request);

            return $this->paginateCollection($data, $this->notificationTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNotification(NotificationRequest $request)
    {
        try {
            $notification = $this->notificationService->save($request->all());

            return $this->item($notification, $this->notificationTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification(int $notificationId)
    {
        try {
            $notification = $this->notificationService->delete($notificationId);

            return $this->returnJsonResponse($notification);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listFaq(Request $request)
    {
        try {
            $data = $this->faqService->getListing($request);

            return $this->paginateCollection($data, $this->faqTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFaq(FaqCreateRequest $request)
    {
        try {
            $faq = $this->faqService->save($request->all());

            return $this->item($faq, $this->faqTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFaq(int $faqId, FaqRequest $request)
    {
        try {
            $faq = $this->faqService->update($request->all(), $faqId);

            return $this->item($faq, $this->faqTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFaq(int $faqId)
    {
        try {
            $faq = $this->faqService->getHubFaq($faqId);

            return $this->item($faq, $this->faqTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFaq(int $faqId)
    {
        try {
            $faq = $this->faqService->delete($faqId);

            return $this->returnJsonResponse($faq);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLegal(int $legalId, PageRequest $request)
    {
        try {
            $legal = $this->pageService->update($request->all(), $legalId);

            return $this->item($legal, $this->pageTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLegal(int $legalId)
    {
        try {
            $legal = $this->pageService->getHubLegal($legalId);

            return $this->item($legal, $this->pageTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStaff(DispensaryUserRequest $request)
    {
        try {
            $dispenUser = $this->dispenUserService->save($request->all());

            return $this->item($dispenUser, $this->dispenUserTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
