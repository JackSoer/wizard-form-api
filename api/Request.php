<?php

namespace Api;

class Request
{
    public function __construct()
    {

    }

    public static function objKeysToMySqlKeys(array $objKeys): array
    {
        $result = [];

        foreach ($objKeys as $key) {
            $str = preg_replace("/[A-Z]/", '_' . "$0", $key);

            array_push($result, strtolower($str));
        };

        return $result;
    }

    public static function strToMySqlKey(string $str): string
    {
        $result = '';

        $result = preg_replace("/[A-Z]/", '_' . "$0", $str);

        return strtolower($result);
    }

    public static function getStoreParams(array $obj, array $nullableKeys = []): array
    {
        $params = [];

        $underscoreKeys = static::objKeysToMySqlKeys(array_keys($obj));

        foreach ($underscoreKeys as $underscoreKey) {
            $camelCaseKey = Response::transformStrToCamelCase($underscoreKey);

            $params[$underscoreKey] = $obj[$camelCaseKey];
        }

        foreach ($nullableKeys as $nullableKey) {
            $camelCaseNullableKey = static::strToMySqlKey($nullableKey);
            $params[$camelCaseNullableKey] = $obj[$nullableKey] ?? null;
        }

        return $params;
    }

    public static function getPatchParams(array $obj, array $oldObj): array
    {
        $params = [];

        $underscoreKeys = static::objKeysToMySqlKeys(array_keys($oldObj));

        foreach ($underscoreKeys as $underscoreKey) {
            $camelCaseKey = Response::transformStrToCamelCase($underscoreKey);

            $params[$underscoreKey] = $obj[$camelCaseKey] ?? $oldObj[$underscoreKey];
        }

        return $params;
    }
}
