<?php

namespace App\Http\Controllers\Admin\Dispensary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dispensary\DispensaryNoteRequest;
use App\Http\Requests\Admin\Dispensary\DispensaryUpdateRequest;
use App\Transformers\Admin\Dispensary\DispensaryListTransformer;
use App\Transformers\Admin\Dispensary\DispensaryNoteTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Dispensary\DispensaryRequest;
use App\Transformers\Admin\Dispensary\DispensaryTransformer;
use App\Transformers\Hub\Dispensary\DispensaryTransformer as HubDispensaryTransformer;
use App\Services\Admin\Dispensary\DispensaryService;
use App\Http\Requests\Admin\Dispensary\DispensaryChangePasswordRequest;
use Illuminate\Validation\ValidationException;

class DispensaryController extends Controller
{
    protected $dispensaryService;
    protected $transformer;
    protected $noteTransformer;
    protected $listTransformer;
    protected $hubTransformer;
    protected $dispensaryId;

    public function __construct(
        DispensaryService $dispensaryService,
        DispensaryTransformer $transformer,
        DispensaryNoteTransformer $noteTransformer,
        DispensaryListTransformer $listTransformer,
        HubDispensaryTransformer $hubTransformer
    ) {
        $this->dispensaryService = $dispensaryService;
        $this->transformer = $transformer;
        $this->noteTransformer = $noteTransformer;
        $this->listTransformer = $listTransformer;
        $this->hubTransformer = $hubTransformer;
        $this->dispensaryId = tenant('id');
    }

    public function list(Request $request)
    {
        $data = $this->dispensaryService->getListing($request);
        return $this->paginateCollection($data, $this->listTransformer);
    }

    public function store(DispensaryRequest $request)
    {
        try {
            $dispensary = $this->dispensaryService->saveOrUpdate($request);
            if (!$dispensary['success']) {
                return response()->json(['message' => $dispensary['message']]);
            }

            return $this->item($dispensary['data'], $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function update(DispensaryUpdateRequest $request)
    {
        try {

            $dispensaryId = tenant('id') ?? $this->validateDispensaryId($request);
            $dispensary = $this->dispensaryService->saveOrUpdate($request, $dispensaryId);
            if (!$dispensary['success']) {
                return response()->json(['message' => $dispensary['message']]);
            }

            return $this->item($dispensary['data'], $this->transformer);
        } catch (ValidationException $e) {
            return $this->abortJsonResponse($e->errors());
        }
    }

    public function delete(Request $request)
    {
        try {
            $dispensaryId = $this->validateDispensaryId($request);
            $dispensary = $this->dispensaryService->delete($dispensaryId);
            if (!$dispensary['success']) {
                return response()->json(['message' => $dispensary['message']]);
            }
            return response()->json(['message' => __('message.dispensary_deleted')]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getDispensary(Request $request)
    {
        try {
            $dispensaryId = tenant('id') ?? $this->validateDispensaryId($request);
            $dispensary = $this->dispensaryService->getDispensary($dispensaryId);
            if (!$dispensary['success']) {
                return response()->json(['message' => $dispensary['message']]);
            }
            return $this->item($dispensary['data'], $this->transformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }



    public function addNote(DispensaryNoteRequest $request)
    {
        try {
            $notes = $this->dispensaryService->addNote($request);
            if (!$notes['success']) {
                return response()->json(['message' => $notes['message']]);
            }
            return $this->item($notes['data'], $this->noteTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function getNotes(Request $request)
    {
        try {
            $this->validateDispensaryId($request);
            $notes = $this->dispensaryService->getNotes($request);
            if (!$notes['success']) {
                return response()->json(['message' => $notes['message']]);
            }
            return $this->collection($notes['data'], $this->noteTransformer);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e->getMessage());
        }
    }

    public function sendMail(int $dispensaryId, Request $request)
    {
        try {
            $sentMail = $this->dispensaryService->sendMail($dispensaryId, $request->all());
            if (!$sentMail['success']) {
                return response()->json(['message' => $sentMail['message']]);
            }
            return $this->returnJsonResponse($sentMail['data']);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    public function changePassword(DispensaryChangePasswordRequest $request)
    {
        try {
            $dispensary = $this->dispensaryService->changePassword($request);
            return response()->json(['message' => $dispensary['message']]);
        } catch (\Exception $e) {
            return $this->abortJsonResponse($e);
        }
    }

    protected function validateDispensaryId(Request $request)
    {
        $dispensaryId = $request->route('dispensaryId');
        $request->merge(['dispensary_id' => $dispensaryId]);
        $request->validate(['dispensary_id' => 'required|exists:dispensaries,id']);
        return $dispensaryId;
    }
}
