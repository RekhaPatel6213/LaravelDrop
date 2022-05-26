<?php

namespace App\Services\Admin\Dispensary;

use App\Models\Repositories\Admin\Dispensary\DispensaryHourSetRepository;
use App\Models\Repositories\Admin\Dispensary\DispensaryTimingRepository;
use App\Models\Repositories\Territory\TerritoryRepository;

class DispensaryHourSetService
{
    protected $repository;
    protected $timingRepository;
    protected $territoryRepository;

    public function __construct(
        DispensaryHourSetRepository $repository,
        DispensaryTimingRepository $timingRepository,
        TerritoryRepository $territoryRepository
    ) {
        $this->repository = $repository;
        $this->timingRepository = $timingRepository;
        $this->territoryRepository = $territoryRepository;
    }

    public function updateShopTimings($request)
    {
        try {
            $requestData = $request->all();

            $sets = $requestData['data'];
            foreach ($sets as $setId => $data) {
                $hourData = [
                    'name' => $data['name']
                ];
                $hourSet = $this->repository->findWhere(['id' => $setId])->first();
                $hourSetObj = $this->repository->store($hourData, $hourSet);

                foreach ($data['timings'] as $day => $fromToTimes) {
                    $daysArr = config('constants.DAYNUMBERS_OF_WEEK');
                    $dataIns = [
                        'from_time' => $fromToTimes['from_time'] ?? '10:00:00',
                        'to_time' => $fromToTimes['to_time'] ?? '10:00:00',
                    ];
                    $this->timingRepository->updateOrCreate([
                        'dispensary_hour_set_id' => $hourSetObj->id,
                        'day' => $daysArr[$day]
                    ], $dataIns);
                }
                foreach ($data['territories'] as $territory) {
                    $this->territoryRepository->update(['hour_set_id' => $hourSetObj->id], $territory);
                }
            }

            $timingsData = $this->getDispensaryTimingsData();
            return ['success' => true, 'data' => $timingsData];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getDispensaryTimingsData()
    {
        $hourSets = $this->repository->get();
        $data = [];
        foreach ($hourSets as $hourSet) {
            $territories = $this->territoryRepository->getHourSetTerritoryIds($hourSet->id);
            $data[$hourSet->id] = [
                'name' => $hourSet->name,
                'territories' => $territories,
                'timings' => $this->getHourSetTimings($hourSet)
            ];
        }

        return $data;
    }

    public function delete($hourSetId)
    {
        try {
            $this->repository->delete($hourSetId);
            return ['success' => true, 'data' => []];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getDispensaryTimings()
    {
        try {
            $timingsData = $this->getDispensaryTimingsData();
            return ['success' => true, 'data' => $timingsData];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getHourSetTimings($hourSet)
    {
        $timings = $this->timingRepository->findWhere(['dispensary_hour_set_id' => $hourSet->id]);
        $dayArr = array_flip(config('constants.DAYNUMBERS_OF_WEEK'));
        $data = [];
        foreach ($timings as $timing) {
            $data[$dayArr[$timing->day]] = [$timing->from_time, $timing->to_time];
        }
        return $data;
    }
}
