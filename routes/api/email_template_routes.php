<?php

    /**
     *  @OA\Schema(
     *   schema="EmailTemplateList",
     *   required={"data"},
     *   @OA\Property(property="meta", ref="#/components/schemas/StandardPaginationMeta")
     *  )
     *
     *
     * @OA\Schema(schema="EmailTemplateInputData",
     *      @OA\Property(property="dispensary_id", type="string", description="Dispensary Id", example="1"),
     *      @OA\Property(property="mailable", type="string", description="Mailable Class", example="App\Mail\WelcomeMail"),
     *      @OA\Property(property="subject", type="string", description="Last Name", example="Welcome, ET_FIRST_NAME"),
     *      @OA\Property(property="text_template", type="string", description="Text Template",
     *      example="Hello, ET_FIRST_NAME!, Welcome to, ET_DISPENSARY_NAME, Thank you"),
     *      @OA\Property(property="html_template", type="string", description="HTML Template",
     *      example="<html><head></head><body><h1>Hello, ET_FIRST_NAME ET_LAST_NAME</h1>
                    <div style='border: 5px outset red;background-color: lightblue;text-align: center;'>
                    <h2>Welcome to, ET_DISPENSARY_NAME</h2>
                    <p>Now, you are able to access all our products.</p>
                    </div>
                    <p>Thank you</p>
                    <p><i>Team, ET_DISPENSARY_NAME</i></p>
                    </body></html>"),
     *  )
     *
     *
     *
     */

    /**
     * @OA\Get(path="/admin/email-templates",
     *   tags={"Email Templates"},
     *   summary="Email templates listing",
     *   description="Email templates listing",
     *   @OA\Parameter(in="query",name="dispensaryId",description="Dispensary Id", example="1",required=true,@OA\Schema(type="string")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/EmailTemplateList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('email-templates', 'EmailTemplateController@list');


    /**
     * @OA\Get(path="/admin/email-templates/{templateId}",
     *   tags={"Email Templates"},
     *   summary="Single Email template",
     *   description="Get Single Email Template By providing template id",
     *   @OA\Parameter(name="templateId", in="path", description="Template Id", required=true, example="1"),
     *   @OA\Response(response=200,description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('email-templates/{templateId}', 'EmailTemplateController@getSingleEmailTemplate')->where('templateId', '[0-9]+');


    /**
     * @OA\Patch(path="/admin/email-templates/{templateId}",
     *   tags={"Email Templates"},
     *   summary="Update Email Template",
     *   description="Update Email Template by providing template id",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/EmailTemplateInputData"))),
     *   @OA\Parameter(name="templateId", in="path", description="Template Id", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    Route::patch('email-templates/{templateId}', 'EmailTemplateController@update')->where('templateId', '[0-9]+');
