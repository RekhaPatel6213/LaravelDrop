<?php

namespace App\Http\Controllers\Hub;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hub\InventoryRequest;
use App\Http\Requests\Hub\InventoryCreateRequest;
use App\Services\Hub\InventoryService;
use App\Transformers\Hub\InventoryTransformer;
use App\Exceptions\OrderExitException;

/**
 * Class InventoriesController.
 *
 * @package namespace App\Http\Controllers\Hub;
 */
class InventoryController extends Controller
{
    /**
     * @var InventoryService
     */
    protected $inventoryService;

    /**
     * @var InventoryTransformer
     */
    protected $inventoryTransformer;

    /**
     * InventoriesController constructor.
     *
     * @param InventoryService $inventoryService
     * @param InventoryTransformer $inventoryTransformer
     */
    public function __construct(InventoryService $inventoryService, InventoryTransformer $inventoryTransformer)
    {
        $this->inventoryService = $inventoryService;
        $this->inventoryTransformer  = $inventoryTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = $this->inventoryService->getListing($request);
        return $this->paginateCollection($data, $this->inventoryTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  InventoryCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function save(InventoryCreateRequest $request)
    {
        try {
            $inventory = $this->inventoryService->update($request->all());
            return $this->item($inventory, $this->inventoryTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  InventoryRequest $request
     *
     * @return \Illuminate\Http\Response
     */

    public function getInventory(int $inventoryId, InventoryRequest $request)
    {
        try {
            $inventory = $this->inventoryService->getInventory($inventoryId);
            return $this->item($inventory, $this->inventoryTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  InventoryRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $inventoryId, InventoryCreateRequest $request)
    {
        try {
            $inventory = $this->inventoryService->update($request->all(), $inventoryId);
            return $this->item($inventory, $this->inventoryTransformer);
        } catch (OrderExitException $e) {
            return $this->abortJsonResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  InventoryRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(int $inventoryId, InventoryRequest $request)
    {
        try {
            $inventory = $this->inventoryService->delete($inventoryId);
            return $this->returnJsonResponse($inventory);
        } catch (OrderExitException $e) {
            return $this->abortJsonResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
