<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Admin\Dispensary\Dispensary;
use App\Models\Repositories\Admin\Dispensary\DispensaryRepository;
use App\Models\Repositories\Admin\Dispensary\DomainRepository;
use App\Events\Admin\Dispensary\DispensaryCreated;
use App\Events\Admin\Dispensary\DispensaryUpdated;
use Spatie\Activitylog\Models\Activity;
use App\Models\Admin\Dispensary\DispensaryUser;
use App\Notifications\Admin\Dispensary\SendPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DispensaryService
{
    protected $dispensaryRepository;

    public function __construct(
        DispensaryRepository $dispensaryRepository,
        DomainRepository $domainRepository
    ) {
        $this->dispensaryRepository = $dispensaryRepository;
        $this->domainRepository = $domainRepository;
    }

    public function saveOrUpdate($request, $dispensaryId = null)
    {
        try {
            $requestData = $request->all();
            $requestData['logoObj'] = $request->file('logo');
            $requestData['headerLogoObj'] = $request->file('header_logo');
            $requestData['appIconObj'] = $request->file('app_icon');

            if (isset($requestData['status']) && !in_array($requestData['status'], Dispensary::DEFAULT_STATUSES)) {
                return ['success' => false, 'message' => __('message.invalid_status')];
            }

            if (!empty($dispensaryId)) {
                $dispensary = $this->dispensaryRepository->find($dispensaryId);
                $dispensaryObj = $this->dispensaryRepository->store($requestData, $dispensary);
                event(new DispensaryUpdated($dispensary, $dispensaryObj, $requestData));
                return ['success' => true, 'data' => $dispensaryObj];
            }

            $alphaId = $this->generateAlphaId();
            $requestData['alpha_id'] = $alphaId;
            $dispensaryObj = $this->dispensaryRepository->store($requestData);
            event(new DispensaryCreated($dispensaryObj, $requestData));

            return ['success' => true, 'data' => $dispensaryObj];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', 'dispensaries.created_at');
        $sortOrder = $request->query('sort', Dispensary::DEFAULT_LIST_ORDER);
        $status = $request->query('dispensaryStatus', Dispensary::DEFAULT_LIST_STATUS);
        $searchString = $request->query('search', '');

        return $this->dispensaryRepository->getListingData($searchString, $sortOn, $sortOrder, $status);
    }

    public function delete(int $dispensaryId)
    {
        $this->dispensaryRepository->delete($dispensaryId);
        return ['success' => true, 'data' => []];
    }

    public function addNote($request)
    {
        $requestData = $request->all();
        $dispensary = $this->dispensaryRepository->find($requestData['dispensary_id']);
        activity()
            ->performedOn($dispensary)
            ->withProperties(['note' => $requestData['note']])
            ->log(Dispensary::CUSTOM_NOTE_SLUG);
        $activity = Activity::all()->last();
        return ['success' => true, 'data' => $activity];
    }

    public function getNotes($request)
    {
        $requestData = $request->all();
        $dispensary = $this->dispensaryRepository->find($requestData['dispensary_id']);
        $activity = Activity::forSubject($dispensary)->get();

        return ['success' => true, 'data' => $activity];
    }

    public function getDispensary(int $dispensaryId)
    {
        $dispensary = $this->dispensaryRepository->find($dispensaryId);
        return ['success' => true, 'data' => $dispensary];
    }
    
    public function generateAlphaId()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphaId  = substr(str_shuffle($chars), 0, 8);
        if ($this->dispensaryRepository->isAlphaExists($alphaId)) {
            $this->generateAlphaId();
        }
        return $alphaId;
    }

    public function sendMail(int $dispensaryId, array $request = null)
    {
        $dispensary = $this->dispensaryRepository->find($dispensaryId);
        if ($dispensary) {
            $user = DispensaryUser::whereDispensaryId($dispensary->id)->first();
            $token = $user->getPasswordToken();
            $user->notify(new SendPasswordNotification($user, $dispensary->name));

            return ['success' => true, 'data' => $dispensary];
        } else {
            return ['success' => false, 'message'=> __('message.dispensary_not_found')];
        }
    }

    public function changePassword($request)
    {
        $input = $request->all();
        $dispensaryUser = Auth::guard(config('app.dispensary_guard'))->user();
        if ((Hash::check($input['old_password'], $dispensaryUser->password)) === true) {
            $dispensaryUser->password = Hash::make($input['new_password']);
            $dispensaryUser->save();
            return ['success' => true, 'message'=> __('passwords.update_password')];
        } elseif ((Hash::check(request('old_password'), $dispensaryUser->password)) === false) {
            return ['success' => false, 'message'=> __('passwords.check_old_password')];
        }
    }
}
