<?php

namespace App\Services\Admin;

use App\Helpers\EmailTemplateHelper;
use App\Http\Requests\Admin\EmailTemplateRequest;
use App\MailTemplates\DispensaryMailTemplate;

class EmailTemplateService
{
    const TABLE_KEYS = ['subject', 'html_template', 'text_template'];
    public function __construct()
    {
        //
    }

    public function getListing($request)
    {
        $dispensaryId = $request->query('dispensaryId');
        $allData = DispensaryMailTemplate::where('dispensary_id', $dispensaryId)->get();
        return $allData;
    }

    public function update($args, $templateId)
    {
        if (null === ($mailTemplate = DispensaryMailTemplate::find($templateId))) {
            return ['success' => false, 'message' => __('message.invalid_template')];
        }

        if (!empty($update = $this->filterUpdateData($args))) {
            $update = EmailTemplateHelper::encodeDecodeTemplateVars($update, true);
            DispensaryMailTemplate::where('id', $templateId)->update($update);
        }
        return $mailTemplate;
    }

    public function find(int $templateId)
    {
        return DispensaryMailTemplate::findOrFail($templateId);
    }

    protected function filterUpdateData($args): array
    {
        $update = [];
        foreach (self::TABLE_KEYS as $key) {
            if (isset($args[$key])) {
                $update[$key] = $args[$key];
            }
        }

        return $update;
    }
}
