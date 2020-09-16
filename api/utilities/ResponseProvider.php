<?php
/**
 * Created by PhpStorm.
 * User: rettiwer
 * Date: 15.04.2019
 * Time: 14:40
 */

namespace App\Utilities;


class ResponseProvider
{
    public function withOkData($response, $message, $data)
    {
        return $response->withJson(['success' => true, 'message' => $message, 'data' => $data]);
    }

    public function withOk($response, $message)
    {
        return $response->withJson(['success' => true, 'message' => $message]);
    }

    public function withError($response, $message, $statusCode)
    {
        return $response->withJson(['success' => false, 'message' => $message], $statusCode);
    }
}