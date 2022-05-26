<?php
namespace App\Helpers;

class EmailTemplateHelper
{

    /**
     * Convert Template variables
     * @param $data
     * @param $encode
     * @return array
     */
    public static function encodeDecodeTemplateVars($data, $encode = true)
    {
        $templateVars = config('email-template.TEMPLATE_VARS');
        $keys = ['subject', 'html_template', 'text_template'];

        if (is_array($data)) {
            $data = array_map(function ($item) use ($templateVars, $encode, $keys) {
                if (in_array($item, $keys)) {
                    if ($encode) {
                        return str_replace(array_keys($templateVars), array_values($templateVars), $item);
                    }
                    return str_replace(array_values($templateVars), array_keys($templateVars), $item);
                }
                return $item;
            }, $data);

            return $data;
        }

        if ($encode) {
            return str_replace(array_keys($templateVars), array_values($templateVars), $data);
        }
        return str_replace(array_values($templateVars), array_keys($templateVars), $data);
    }
}
