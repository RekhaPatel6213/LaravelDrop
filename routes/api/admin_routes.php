<?php
    /**
     * @OA\Get(path="/admin",
     *   tags={"Admin User"},
     *   summary="Admin Listing.",
     *   description="List All Admin Data",
     *   @OA\Parameter(in="query", name="first_name", description="Search Using First Name"),
     *   @OA\Parameter(in="query", name="last_name", description="Search Using Last Name"),
     *   @OA\Parameter(in="query", name="created_at_from", description="Search Admin from Created Date."),
     *   @OA\Parameter(in="query", name="created_at_till", description="Search Admin to Created Date."),
     *   @OA\Parameter(in="query", name="except_admin_id", description="List all the Admin without specified id."),
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/AdminSortsOn")),
     *   @OA\Parameter(in="query", name="includes", description="includes", style="form", explode=false,
     *   @OA\Schema(ref="#/components/schemas/AdminUserInclude")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/AdminList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('', 'AdminUserController@adminList');

    /**
     * @OA\Post(
     *      tags = {"Admin User"},
     *      path = "/admin",
     *      summary = "Create New Master user",
     *      description = "API for creating new master user",
     *      operationId="createMasterUser",
     *      @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/AdminInputData"))),
     *      @OA\Response(response=200, description="success response", @OA\JsonContent(ref="#/components/schemas/AdminDetail")),
     *      @OA\Response(response=401, description="Invalid Credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Invalid Client Code"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('', 'AdminUserController@create');

    /**
     * @OA\Get(path="/admin/{adminId}",
     *   tags={"Admin User"},
     *   summary="Get Admin User",
     *   description="Get Admin User Data",
     *   @OA\Parameter(name="adminId", in="path", description="Admin ID", required=true, example="1"),
     *   @OA\Parameter(name="includes", in="query", description="description",
     *     style="form",
     *     explode=false,
     *   @OA\Schema(ref="#/components/schemas/AdminUserInclude")),
     *   @OA\Response(response=200, description="Get Single Admin User Record",@OA\JsonContent(ref="#/components/schemas/AdminDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Lens Data Not Found"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('{adminId}', 'AdminUserController@getAdmin')->where('adminId', '[0-9]+');

    /**
     * @OA\Put(path="/admin/{adminId}",
     *   tags={"Admin User"},
     *   summary="Admin User Update Form",
     *   description="Update Admin User",
     *   requestBody={"$ref": "#/components/requestBodies/AdminUserRequest"},
     *   @OA\Parameter(name="adminId", in="path", description="Admin ID", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/AdminDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::put('{adminId}', 'AdminUserController@update')->where('adminId', '[0-9]+');

    /**
     * @OA\Patch(path="/admin/{adminId}",
     *   tags={"Admin User"},
     *   summary="Admin User Update Form",
     *   description="Update Admin User",
     *   requestBody={"$ref": "#/components/requestBodies/AdminUserRequest"},
     *   @OA\Parameter(name="adminId", in="path", description="Admin ID", required=true, example="1"),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/AdminDetail")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('{adminId}', 'AdminUserController@update')->where('adminId', '[0-9]+');

    /**
     * @OA\Delete(path="/admin/{adminId}",
     *   tags={"Admin User"},
     *   summary="Delete Admin User",
     *   description="Delete Admin User By ID",
     *   @OA\Parameter(name="adminId", in="path", description="Admin ID", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation",@OA\JsonContent(ref="#/components/schemas/SuccessMessage")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('{adminId}', 'AdminUserController@delete')->where('adminId', '[0-9]+');
