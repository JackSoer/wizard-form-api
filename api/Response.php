<?php

namespace Api;

class Response
{
    public function __construct()
    {

    }

    public static function jsonResponse(array $data, $code = 200)
    {
        http_response_code($code);

        $data['status'] = $code;

        return json_encode($data);
    }

    public static function transformObjToCamelCase(array $obj)
    {
        foreach ($obj as $key => $value) {
            $keyArray = explode('_', $key);

            if (count($keyArray) > 1) {
                $keyArray[1] = ucfirst($keyArray[1]);
                unset($obj[$key]);
            }

            $camelCaseKey = join('', $keyArray);
            $obj[$camelCaseKey] = $value ?? null;
        }

        return $obj;
    }

    public static function transformStrToCamelCase(string $str)
    {
        $strArray = explode('_', $str);

        if (count($strArray) > 1) {
            $strArray[1] = ucfirst($strArray[1]);
        }

        $camelCaseStr = join('', $strArray);

        return $camelCaseStr;
    }
}
