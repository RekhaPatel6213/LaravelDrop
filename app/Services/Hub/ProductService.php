<?php

namespace App\Services\Hub;

use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Models\Hub\Product;
use App\Models\Repositories\GenericImportRepository;
use App\Models\Repositories\Hub\ProductRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Vanilo\Product\Models\ProductState;

class ProductService
{
    protected $repository;
    protected $importRepository;

    public function __construct(ProductRepository $productRepository, GenericImportRepository $importRepository)
    {
        $this->repository = $productRepository;
        $this->importRepository = $importRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null);
        $state = $requestData->query('state', ProductState::ACTIVE);
        $sortOn = $requestData->query('sortOn', 'products.priority');
        $sortOrder = $requestData->query('sort', 'asc');

        return $this->repository->list($search, $state, $sortOn, $sortOrder);
    }

    public function allList($requestData)
    {
        $search = $requestData->query('search', null);
        $state = $requestData->query('state', ProductState::ACTIVE);
        $sortOn = $requestData->query('sortOn', 'products.priority');
        $sortOrder = $requestData->query('sort', 'desc');

        return $this->repository->getQueryBuilder($search, $state, $sortOn, $sortOrder);
    }

    public function get(int $productId)
    {
        $product = $this->repository->get($productId);
        if (null === $product) {
            return ['success' => false, 'message' => __('message.notFound', ['name' => __('product.product')])];
        }

        return $product;
    }

    public function create(array $requestData)
    {
        return $this->repository->updateOrCreate($requestData);
    }

    public function update(array $requestData, int $productId = null)
    {
        $product = $this->repository->get($productId, ['productDetails']);
        if (null === $product) {
            return ['success' => false, 'message' => __('message.notFound', ['name' => __('product.product')])];
        }

        return $this->repository->updateOrCreate($requestData, $product);
    }

    public function updateAll(array $requestData)
    {
        return $this->repository->updateAll($requestData);
    }

    public function delete(int $productId)
    {
        $this->repository->get($productId)->delete();

        return ['message' => __('message.deleteSuccess', ['name' => __('product.product')])];
    }

    public function deleteAll(array $requestData)
    {
        return $this->repository->deleteAll($requestData);
    }

    public function ajaxList()
    {
        return $this->repository->ajaxList();
    }
    
    public function getExportData($type)
    {
        $fileName = tenant('id').'_'.config('constants.ExportFilePrefix').'Inventory_'.time().'.'.strtolower($type);

        if ($type === Product::PDF) {
            $products = $this->repository->getExportData($type);

            $pdf = PDF::loadView('export/product', compact('products'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $date = Carbon::now()->format('d-m-Y H:i:s');
            $canvas->page_text(100 - $canvas->get_text_width($date, null, 8), 570, $date, null, 10, [0, 0, 0]);
            $canvas->page_text(775, 570, 'Page {PAGE_NUM}/{PAGE_COUNT}', null, 10, [0, 0, 0]);
            $pdf->save(storage_path().'/'.$fileName);

            return $pdf->download($fileName);
        }

        $exportClass = \App::make(ProductExport::class);

        return Excel::download($exportClass, $fileName);
    }

    public function importHistory()
    {
        return $this->importRepository->with(['user'])->findWhere(
            [
                'import_type' => ProductImport::IMPORT_KEY,
                'status' => ProductImport::COMPLETED,
            ]
        );
    }

    public function importData($request)
    {
        if ($request->hasFile('csv_import')) {
            $file = $request->file('csv_import');
            $path = $file->store('public/imports');
            $importClass = \App::make(ProductImport::class);
            Excel::import($importClass, $path);

            return $this->importRepository->getLastImportData(ProductImport::IMPORT_KEY);
        }

        return ['errorMessage' => 'error'];
    }

    public function importPreview(int $previewId, array $requestData)
    {
        $productStocks = $requestData['product'];
        $importData = $this->importRepository->getPendingPreviewData($previewId)->toArray();
        if ($importData) {
            $key = 0;
            foreach ($importData['data'] as $pKey => $priority) {
                foreach ($priority as $cKey => $parentCategory) {
                    foreach ($parentCategory as $dKey => $data) {
                        $product = null;
                        if ($data['product_id'] > 0) {
                            $product = $this->repository->get($data['product_id'], ['productDetails']);
                        }

                        $quantityData = $productStocks[$key] ?? null;
                        if ($quantityData !== null) {
                            $data['is_unlimited'] = $quantityData['is_unlimited'];
                            if ($data['quantity_type'] === Product::PREPACKAGED) {
                                if (isset($quantityData['product_details']) && count($quantityData['product_details']) > 0 && count($quantityData['product_details']) === count($data['product_details'])) {
                                    foreach ($data['product_details'] as $sKey => $detail) {
                                        $data['product_details'][$sKey]['stock'] = $data['is_unlimited'] === Product::YES ? 0 : $quantityData['product_details'][$sKey]['stock'];
                                        $importData['data'][$pKey][$cKey][$dKey]['product_details'][$sKey]['stock'] = $data['product_details'][$sKey]['stock'];
                                    }
                                }
                            }

                            if ($data['quantity_type'] !== Product::PREPACKAGED) {
                                $data['stock'] = $data['is_unlimited'] === Product::YES ? 0 : $quantityData['stock'];
                                $importData['data'][$pKey][$cKey][$dKey]['stock'] = $data['stock'];
                            }
                        }

                        $this->repository->updateOrCreate($data, $product);
                        ++$key;
                    }
                }
            }
            $this->importRepository->update(['data' => $importData['data'], 'status' => 'COMPLETED'], $previewId);
        }

        return ['message' => __('message.importSuccess', ['name' => __('product.product')])];
    }

    public function importDetail(int $previewId)
    {
        return $this->importRepository->getPreviewData($previewId);
    }
}
