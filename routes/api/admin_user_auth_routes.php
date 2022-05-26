<?php
     /**
      * @OA\Post(
      *    tags = {"Admin Authenticate"},
      *    path="/admin/login",
      *    summary = "Admin Logged In",
      *    description = "API to Login by email, password",
      *    operationId="adminAuthLogin",
      *    @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/loginInputData"))),
      *    @OA\Response(response=200, description="Valid new JWT Token", @OA\JsonContent(ref="#/components/schemas/JWTToken")),
      *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
      *    @OA\Response(response=403, description="Forbidden"),
      *    @OA\Response(response=404, description="Invalid Client Code"),
      *    @OA\Response(response=500, description="Internal Server Error")
      * )
      */
    Route::post('login','Auth\LoginController@login')->name('admin.login');

    /**
     * @OA\Post(
     *    tags={"Admin Authenticate"},
     *    path="/admin/send-forgot-password-link",
     *    summary="Send forgot password link to Admin User.",
     *    description="Send forgot password link to Admin User.",
     *    requestBody={"$ref":"#/components/requestBodies/ForgotPassword"},
     *    @OA\Response(response=200, description="on Sucess of send forgot password link"),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     *  )
     */
    Route::post('send-forgot-password-link', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.forgot.password');

    /**
     * @OA\Post(
     *    tags={"Admin Authenticate"},
     *    path="/admin/reset-password",
     *    summary="Reset Admin User Password",
     *    description="Reset Admin User Password",
     *    requestBody={"$ref":"#/components/requestBodies/ResetPassword"},
     *    @OA\Response(response=200, description="password reset sucess"),
     *    @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *    @OA\Response(response=403, description="Forbidden"),
     *    @OA\Response(response=404, description="Invalid Client Code"),
     *    @OA\Response(response=500, description="Internal Server Error")
     *  )
     */
    Route::post('reset-password', 'Auth\ResetPasswordController@reset')->name('admin.password.reset');

    /**
     * @OA\POST(
     *   tags={"Admin Authenticate"},
     *   path="/admin/logout",
     *   summary="Logout Admin User",
     *   description="Logout Admin User",
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('logout', 'Auth\LoginController@logout');
