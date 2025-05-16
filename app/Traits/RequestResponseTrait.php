<?php

namespace App\Traits;

trait RequestResponseTrait
{
    public function successJsonResponse($responseMessage,$responseData=null)
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => $responseMessage,
            'content' => $responseData
        ]);
    }

    public function exceptionJsonResponse($responseData)
    {
        \Log::info(['pageError' => $responseData->getMessage()]);
        return response()->json([
            'code' => 500,
            'status' => 'error',
            'message' => 'Something went wrong.',
            'content' => $responseData->getMessage()
        ]);
    }
}
