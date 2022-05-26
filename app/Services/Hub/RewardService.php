<?php

namespace App\Services\Hub;

use App\Models\Hub\Reward;
use App\Models\Repositories\Hub\RewardRepository;
use App\Http\Traits\DispensaryTrait;
use App\Http\Traits\MediaTrait;

class RewardService
{
    use DispensaryTrait, MediaTrait;
    private $rewardRepository;
    private $alias;
    private $sku;

    public function __construct(RewardRepository $rewardRepository) {
        $this->rewardRepository = $rewardRepository;
        $this->alias = 'rewards';
        $this->sku = $this->generateUniqueId('Hub\Reward', 'sku');
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getListing($request)
    {
        $sortOn = $request->query('sortOn', $this->alias . '.created_at');
        $sortOrder = $request->query('sort', Reward::DEFAULT_LIST_ORDER);
        $searchString = $request->query('search', '');

        return $this->rewardRepository->getListingData($searchString, $sortOn, $sortOrder);
    }

    /**
     * @param $requestData
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function save($requestData)
    {
        $requestData['sku'] = (!isset($requestData['sku'])) ? $this->sku : $requestData['sku'];
        $reward = $this->rewardRepository->create($requestData);
        $this->createMedia($reward, 'logo');
        return $reward;
    }

    /**
     * @param int $rewardId
     * @return mixed
     */
    public function getReward(int $rewardId)
    {
        return $this->rewardRepository->find($rewardId);
    }

    /**
     * @param $requestData
     * @param $rewardId
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update($requestData, $rewardId)
    {
        $reward = $this->rewardRepository->update($requestData, $rewardId);
        if (request()->method() !== 'PATCH') {
            $this->createMedia($reward, 'logo');
        }
        return $reward;
    }

    /**
     * @param int $rewardId
     * @return array
     */
    public function delete(int $rewardId)
    {
        $this->rewardRepository->delete($rewardId);
        return ['message' => __('message.deleteSuccess', ['name' => __('Reward')])];
    }
}
