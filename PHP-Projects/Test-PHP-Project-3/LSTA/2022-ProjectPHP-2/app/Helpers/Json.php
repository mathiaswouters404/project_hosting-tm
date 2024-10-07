<?php

namespace App\Helpers;

class Json
{
    /**
     * Dump data as json (add ?json to URL)
     * Dump the given variable and ends execution of the script (add ?dd to URL)
     *
     * @param mixed $data string, array, associative array object
     * @param bool $onlyInDebugMode runs only in debug mode: default = true
     * @version 1.0
     */
    public function dump($data = null, bool $onlyInDebugMode = true)
    {
        $show = !(($onlyInDebugMode === true && env('APP_DEBUG') === false));
        if (array_key_exists('json', app('request')->query()) && $show) {
            header('Content-Type: application/json');
            die(json_encode($data));
        } else if (array_key_exists('dd', app('request')->query()) && $show) {
            dd($data);
        }
    }

    /**
     * Creates and stores a json response
     * @param string $type the type of the response ['error', 'success',...]
     * @param string $text the message of the response
     * @return string[]
     */
    public function createJsonResponse(string $type, string $text): array
    {
        return [
            'type' => $type,
            'text' => $text
        ];
    }
}
