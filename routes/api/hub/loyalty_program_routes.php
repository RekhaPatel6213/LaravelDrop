<?php
    /**
     * @OA\Get(path="/hub/program",
     *   tags={"LoyaltyProgram"},
     *   summary="Loyalty Program Listing.",
     *   @OA\Parameter(ref="#/components/parameters/sort"),
     *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/ProgramSortsOn")),
     *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/ProgramList")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     */
    Route::get('program', 'LoyaltyProgramController@list')->name('loyalty_program.list');

    /**
     * @OA\Get(path="/hub/program/defaults",
     *   tags={"LoyaltyProgram"},
     *   summary="Defaults Loyalty Program details",
     *   description="Defaults Loyalty Program details",
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/LoyaltyDefaultsRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('program/defaults', 'LoyaltyProgramController@getDefaults')->name('loyalty_program.defaults');

    /**
     * @OA\Post(path="/hub/program/defaults",
     *   tags={"LoyaltyProgram"},
     *   summary="Update default Loyalty Program",
     *   description="Update default Loyalty Program",
     *   operationId="updateDefaults",
     *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/LoyaltyDefaults"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/LoyaltyDefaultsRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('program/defaults', 'LoyaltyProgramController@updateDefaults')->name('loyalty_program.update_defaults');


    /**
     * @OA\Get(path="/hub/program/{programId}",
     *   tags={"LoyaltyProgram"},
     *   summary="Loyalty Program details",
     *   description="Loyalty Program details",
     *   @OA\Parameter(name="programId", in="path", description="Loyalty Program id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation", @OA\JsonContent(ref="#/components/schemas/ProgramInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::get('program/{programId}', 'LoyaltyProgramController@getProgram')->where('programId', '[0-9]+')->name('loyalty_program.get_program');


    /**
     * @OA\Post(path="/hub/program",
     *   tags={"LoyaltyProgram"},
     *   summary="Add Loyalty Program",
     *   description="Add Loyalty Program",
     *   operationId="store",
     *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProgramInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/ProgramInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('program', 'LoyaltyProgramController@store')->name('loyalty_program.store');


    /**
     * @OA\post(path="/hub/program/{programId}",
     *   tags={"LoyaltyProgram"},
     *   summary="Update Loyalty Program",
     *   description="Update Loyalty Program",
     *   @OA\Parameter(name="programId", in="path", description="Loyalty Program id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProgramInputData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/ProgramInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::post('program/{programId}', 'LoyaltyProgramController@store')->where('programId', '[0-9]+')->name('loyalty_program.update');


    /**
     * @OA\patch(path="/hub/program/{programId}",
     *   tags={"LoyaltyProgram"},
     *   summary="Update Loyalty Program",
     *   description="Update Loyalty Program",
     *   @OA\Parameter(name="programId", in="path", description="Loyalty Program id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/ProgramPatchData"))),
     *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/ProgramInputDataRes")),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::patch('program/{programId}', 'LoyaltyProgramController@update')->where('programId', '[0-9]+')->name('loyalty_program.patch');


    /**
     * @OA\Delete(path="/hub/program/{programId}",
     *   tags={"LoyaltyProgram"},
     *   summary="Delete Loyalty Program",
     *   description="Delete Loyalty Program",
     *   @OA\Parameter(name="programId", in="path", description="Loyalty Program id", required=true, example="1", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="successful operation"),
     *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Invalid Client Code"),
     *   @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    Route::delete('program/{programId}', 'LoyaltyProgramController@delete')->where('programId', '[0-9]+')->name('loyalty_program.delete');

