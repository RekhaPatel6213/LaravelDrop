<?php

namespace App\Services\Hub;

use App\Models\Repositories\Hub\MessageBoxRepository;
use Illuminate\Support\Facades\Auth;

class MessageBoxService
{

    protected $repository;

    public function __construct(MessageBoxRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        return $this->repository->get();
    }

    public function store($args, $modelId = null)
    {
        $model = null;
        $args['position'] = $this->repository->getPosition();
        if ($modelId !== null) {
            $model = $this->repository->find($modelId);
            unset($args['position']);
        }
        $args['added_by'] = Auth()->user()->id;

        return $this->repository->store($args, $model);
    }

    public function reorderMessages($args)
    {
        $messageIds = $args['message_ids'];
        $valid = $this->repository->count() === count($messageIds);
        throw_if(!$valid, new \Exception('Provide all message ids'));
        foreach ($messageIds as $position => $messageId) {
            $this->repository->update(['position' => $position + 1], $messageId);
        }
        return $this->repository->get();
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }



}
