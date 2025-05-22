<?php
declare(strict_types=1);

namespace App\Traits;
use Illuminate\Http\Response;

trait JsonResponseTrait
{
    public function successJsonResponseWithLimitOffset($message, $option = null, $offset, $limit, $totalCount, $metaData = [], $data = [], $statusCode = Response::HTTP_OK)
    {
        $response = [
            'code'    => $statusCode,
            'message' => $message,
            'total'   => $totalCount,
            'meta'    => $metaData
        ];

        if ($option === 'list' || $option === 'search') {
            $response['limit'] = $limit;
            $response['offset'] = $offset;
        }

        $response['data'] = $data;

        return response()->json($response, $statusCode);
    }
    public function successJsonResponse($message, $data = [], $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message,
            'data'      => $data,
        ], $statusCode);
    }

    public function errorJsonResponse($message, $statusCode = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message
        ], $statusCode);
    }

    public function unAuthenticatedJsonResponse($message, $statusCode = Response::HTTP_FORBIDDEN)
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message
        ], $statusCode);
    }

    public function createdJsonResponse($message, $data = [], $statusCode = Response::HTTP_CREATED)
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message,
            'data'      => $data,
        ], $statusCode);
    }

    public function badJsonResponse($message, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'code'      => $statusCode,
            'message'   => $message
        ], $statusCode);
    }
}
