<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\File;

trait MediaTrait
{
    public function createMedia($model, string $type, string $pathToFile = null)
    {
        $requestData = request();
        if ($requestData->hasFile($type)) {
            $file = $requestData->file($type);
            if (($model->hasMedia($type) && $model->getFirstMedia($type)->file_name !== $file->getClientOriginalName()) || !$model->hasMedia($type)) {
                $model->addMedia($requestData->file($type))->toMediaCollection($type);
            }
        }

        if ($pathToFile !== null && File::exists($pathToFile)) {
            $model->addMediaFromUrl($pathToFile)->toMediaCollection($type);
        }
    }

    public function removeMedia($model, $type, $isRemove = 'true')
    {
        if ($isRemove === 'true') {
            $model->clearMediaCollection($type);
        }
    }
}
