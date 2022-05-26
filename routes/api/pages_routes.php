<?php
/**
 * @OA\Get(path="/admin/page",
 *   tags={"Pages"},
 *   summary="Pages Listing.",
 *   description="List All Page Data",
 *   @OA\Parameter(
 *         description="Search String - Enter keyword to search",
 *         in="query",
 *         name="search",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   @OA\Parameter(ref="#/components/parameters/sort"),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/PageList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('page', 'PageController@list');

/**
 * @OA\POST(
 *      tags = {"Pages"},
 *      path = "/admin/page",
 *      summary = "Create New Page",
 *      description = "API for creating new page",
 *      operationId="createPage",
 *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/PageInputData"))),
 *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *      @OA\Response(response=403, description="Forbidden"),
 *      @OA\Response(response=404, description="Invalid Client Code"),
 *      @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('page', 'PageController@create');

/**
 * @OA\Get(path="/admin/page/{pageId}",
 *   tags={"Pages"},
 *   summary="Get Page",
 *   description="Get Page Data",
 *   @OA\Parameter(name="pageId", in="path", description="Page ID", required=true, example="1"),
 *   @OA\Response(response=200, description="Get Single Page Record",@OA\JsonContent(ref="#/components/schemas/Page")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Lens Data Not Found"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('page/{pageId}', 'PageController@getPage')->where('pageId', '[0-9]+');

/**
 * @OA\Put(path="/admin/page/{pageId}",
 *   tags={"Pages"},
 *   summary="Page Update Form",
 *   description="Update Page",
 *   requestBody={"$ref": "#/components/requestBodies/PageRequest"},
 *   @OA\Parameter(name="pageId", in="path", description="Page ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/Page")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::put('page/{pageId}', 'PageController@update')->where('pageId', '[0-9]+');

/**
 * @OA\Patch(path="/admin/page/{pageId}",
 *   tags={"Pages"},
 *   summary="Page Update Form",
 *   description="Update Page",
 *   requestBody={"$ref": "#/components/requestBodies/PageRequest"},
 *   @OA\Parameter(name="pageId", in="path", description="Page ID", required=true, example="1"),
 *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/Page")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('page/{pageId}', 'PageController@update')->where('pageId', '[0-9]+');

/**
 * @OA\Delete(path="/admin/page/{pageId}",
 *   tags={"Pages"},
 *   summary="Delete Page",
 *   description="Delete Page By ID",
 *   @OA\Parameter(name="pageId", in="path", description="Page ID", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation"),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('page/{pageId}', 'PageController@delete')->where('pageId', '[0-9]+');