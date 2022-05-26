<?php
/**
 * @OA\Get(path="/admin/faq",
 *   tags={"FAQ"},
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
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/FaqList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('faq', 'FaqController@list');

/**
 * @OA\POST(
 *      tags = {"FAQ"},
 *      path = "/admin/faq",
 *      summary = "Create FAQ",
 *      description = "API for creating new FAQ",
 *      operationId="createFaq",
 *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/FaqInputData"))),
 *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *      @OA\Response(response=403, description="Forbidden"),
 *      @OA\Response(response=404, description="Invalid Client Code"),
 *      @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('faq', 'FaqController@create');

/**
 * @OA\Get(path="/admin/faq/{faqId}",
 *   tags={"FAQ"},
 *   summary="Get FAQ",
 *   description="Get FAQ Data",
 *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1"),
 *   @OA\Response(response=200, description="Get Single FAQ Record",@OA\JsonContent(ref="#/components/schemas/Faq")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Lens Data Not Found"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('faq/{faqId}', 'FaqController@getFaq')->where('faqId', '[0-9]+');

/**
 * @OA\Put(path="/admin/faq/{faqId}",
 *   tags={"FAQ"},
 *   summary="FAQ Update Form",
 *   description="Update FAQ",
 *   requestBody={"$ref": "#/components/requestBodies/FaqRequest"},
 *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/Faq")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::put('faq/{faqId}', 'FaqController@update')->where('faqId', '[0-9]+');

/**
 * @OA\Patch(path="/admin/faq/{faqId}",
 *   tags={"FAQ"},
 *   summary="FAQ Update Form",
 *   description="Update FAQ",
 *   requestBody={"$ref": "#/components/requestBodies/FaqRequest"},
 *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/Faq")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('faq/{faqId}', 'FaqController@update')->where('faqId', '[0-9]+');

/**
 * @OA\Delete(path="/admin/faq/{faqId}",
 *   tags={"FAQ"},
 *   summary="Delete FAQ",
 *   description="Delete FAQ By ID",
 *   @OA\Parameter(name="faqId", in="path", description="FAQ ID", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation"),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('faq/{faqId}', 'FaqController@delete')->where('faqId', '[0-9]+');