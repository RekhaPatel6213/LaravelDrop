<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Settings\WeedmapsSettings;
use App\Http\Requests\Admin\WeedmapsRequest;
use App\Transformers\Admin\WeedmapsTransformer;

class SettingsController extends Controller
{
    protected $transformer;
    protected $service;
  
    public function __construct()
    {
        $this->service = app('settingsService');
        $this->transformer = new WeedmapsTransformer;
    }

    public function getSettings(WeedmapsSettings $weedmapsSettings)
    {
        return $this->item($weedmapsSettings, $this->transformer);
    }

    public function updateSettings(WeedmapsRequest $request, WeedmapsSettings $weedmapsSettings)
    {
        try {
            $weedmapsSettings = $this->service->saveWeedmapsSettings($weedmapsSettings, $request->input());
            return $this->item($weedmapsSettings, $this->transformer);
        } catch (Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }
}
