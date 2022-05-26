<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\Hub\DispensaryUserService;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $dispensaryUserService;

    public function __construct(DispensaryUserService $dispensaryUserService)
    {
        $this->dispensaryUserService = $dispensaryUserService;
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['message' => trans($response)]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        if(in_array(Route::currentRouteName(), ['dispensary.forgot.password', 'hub.dispensary.password.reset'])) {
            return Password::broker('dispensary_users');
        }
        return Password::broker('admin_users');
    }


    public  function resetPassword(int $dispensaryUserId, Request $request)
    {
        try {
            $dispensaryUser = $this->dispensaryUserService->getDispensaryUser($dispensaryUserId);
            $request->request->add(['email' => $dispensaryUser->email]);
            return $this->sendResetLinkEmail($request);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
