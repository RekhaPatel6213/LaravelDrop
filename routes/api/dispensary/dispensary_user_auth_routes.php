<?php
/**
 * @OA\Post(
 *    tags = {"Dispensary User Authenticate"},
 *    path="/hub/login",
 *    summary = "Dispensary User Logged In",
 *    description = "API to Login by email, password",
 *    operationId="dispensaryUserAuthLogin",
 *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/loginInputData"))),
 *    @OA\Response(response=200, description="Valid new JWT Token", @OA\JsonContent(ref="#/components/schemas/JWTToken")),
 *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *    @OA\Response(response=403, description="Forbidden"),
 *    @OA\Response(response=404, description="Invalid Client Code"),
 *    @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('login','Auth\LoginController@login')->name('dispensary.login');

/**
 * @OA\Post(
 *    tags = {"Dispensary User Authenticate"},
 *    path="/hub/logout",
 *    summary = "Dispensary User Logged Out",
 *    description = "API to Logout",
 *    operationId="dispensaryUserAuthLogout",
 *   @OA\Response(response=200, description="successful operation"),
 *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *    @OA\Response(response=403, description="Forbidden"),
 *    @OA\Response(response=404, description="Invalid Client Code"),
 *    @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('logout','Auth\LoginController@logout');

/**
 * @OA\Post(
 *    tags={"Dispensary User Authenticate"},
 *    path="/hub/send-forgot-password-link",
 *    summary="Send forgot password link to Dispensary User.",
 *    description="Send forgot password link to Dispensary User.",
 *    requestBody={"$ref":"#/components/requestBodies/ForgotPassword"},
 *    @OA\Response(response=200, description="on Success of send forgot password link"),
 *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *    @OA\Response(response=403, description="Forbidden"),
 *    @OA\Response(response=404, description="Invalid Client Code"),
 *    @OA\Response(response=500, description="Internal Server Error")
 *  )
 */
Route::post('send-forgot-password-link', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('dispensary.forgot.password');

/**
 * @OA\Post(
 *    tags={"Dispensary User Authenticate"},
 *    path="/hub/reset-password",
 *    summary="Reset Dispensary User Password",
 *    description="Reset Dispensary User Password",
 *    requestBody={"$ref":"#/components/requestBodies/ResetPassword"},
 *    @OA\Response(response=200, description="password reset success"),
 *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *    @OA\Response(response=403, description="Forbidden"),
 *    @OA\Response(response=404, description="Invalid Client Code"),
 *    @OA\Response(response=500, description="Internal Server Error")
 *  )
 */
Route::post('reset-password', 'Auth\ResetPasswordController@reset')->name('dispensary.password.reset');

/**
 * @OA\Post(path="/hub/setting/dispensary-user/reset-password/{dispensaryUserId}",
 *    tags={"Dispensary User"},
 *    summary="Reset Dispensary User Password",
 *    description="Reset Dispensary User Password",
 *      @OA\Parameter(name="dispensaryUserId", in="path", description="Dispensary user id", required=true, example="1", @OA\Schema(type="integer")),
 *    @OA\Response(response=200, description="password reset success"),
 *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *    @OA\Response(response=403, description="Forbidden"),
 *    @OA\Response(response=404, description="Invalid Client Code"),
 *    @OA\Response(response=500, description="Internal Server Error")
 *  )
 */
//Route::post('setting/dispensary-user/reset-password/{dispensaryUserId}', 'DispensaryUserController@resetPassword')->where('dispensaryUserId', '[0-9]+')->name('hub.dispensary.password.reset');
Route::post('setting/dispensary-user/reset-password/{dispensaryUserId}', 'Auth\ForgotPasswordController@resetPassword')->where('dispensaryUserId', '[0-9]+')->name('hub.dispensary.password.reset');
