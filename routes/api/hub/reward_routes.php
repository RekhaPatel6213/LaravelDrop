<?php
/**
 * @OA\Get(path="/hub/reward",
 *   tags={"Rewards"},
 *   summary="Reward Listing.",
 *   description="List all reward data",
 *   @OA\Parameter(
 *         description="Search By Name - Enter keyword to search by id, name",
 *         in="query",
 *         name="search",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   @OA\Parameter(ref="#/components/parameters/sort"),
 *   @OA\Parameter(in="query", name="sortOn", @OA\Schema(ref="#/components/schemas/RewardSortsOn")),
 *   @OA\Response(response=200,description="successful operation",@OA\JsonContent(ref="#/components/schemas/RewardList")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 *
 */
Route::get('reward', 'RewardController@list');

/**
 * @OA\Get(path="/hub/reward/{rewardId}",
 *   tags={"Rewards"},
 *   summary="Get single reward",
 *   description="Get single reward by reward id",
 *   @OA\Parameter(name="rewardId", in="path", description="reward id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation"),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::get('reward/{rewardId}', 'RewardController@getReward')->where('rewardId', '[0-9]+');


/**
 * @OA\Post(path="/hub/reward",
 *   tags={"Rewards"},
 *   summary="Add a new reward",
 *   description="Add a new reward",
 *   operationId="store",
 *   @OA\RequestBody(required=false, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/RewardInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('reward', 'RewardController@save');

/**
 * @OA\post(path="/hub/reward/{rewardId}",
 *   tags={"Rewards"},
 *   summary="Update a reward",
 *   description="Update a reward",
 *   @OA\Parameter(name="rewardId", in="path", description="Reward id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data",@OA\Schema(ref="#/components/schemas/RewardInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::post('reward/{rewardId}', 'RewardController@update')->where('rewardId', '[0-9]+');


/**
 * @OA\patch(path="/hub/reward/{rewardId}",
 *   tags={"Rewards"},
 *   summary="Update field(s) reward",
 *   description="Update field(s) reward",
 *   @OA\Parameter(name="rewardId", in="path", description="Reward id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="application/json",@OA\Schema(ref="#/components/schemas/RewardInputData"))),
 *   @OA\Response(response=200,description="successful operation", @OA\JsonContent(ref="#/components/schemas/SuccessResponse")),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::patch('reward/{rewardId}', 'RewardController@update')->where('rewardId', '[0-9]+');


/**
 * @OA\Delete(path="/hub/reward/{rewardId}",
 *   tags={"Rewards"},
 *   summary="Delete single reward",
 *   description="Delete single reward by reward id",
 *   @OA\Parameter(name="rewardId", in="path", description="Reward id", required=true, example="1", @OA\Schema(type="integer")),
 *   @OA\Response(response=200, description="successful operation"),
 *   @OA\Response(response=401, description="Invalid credential", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
 *   @OA\Response(response=403, description="Forbidden"),
 *   @OA\Response(response=404, description="Invalid Client Code"),
 *   @OA\Response(response=500, description="Internal Server Error")
 * )
 */
Route::delete('reward/{rewardId}', 'RewardController@delete')->where('rewardId', '[0-9]+');

