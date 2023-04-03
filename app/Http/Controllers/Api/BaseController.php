<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const PAGINATION = 10;

    private $statusCode = Response::HTTP_OK;
    private $data = [];

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message = 'Whoops, looks like something went wrong')
    {
        return $this
            ->respond([
                'message' => __($message),
            ]);
    }

    /**
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithSuccess($message = '')
    {
        return $this->setStatusCode(Response::HTTP_OK)
            ->respond([
                'status' => $this->getStatusCode(),
                'message' => __($message),
                'data' => $this->getData()
            ]);
    }

    /**
     * @param $messages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondValidationFails($messages)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->respond([
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => __($messages),
            ]);
    }

    /**
     * @param       $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * if the api request has formatted.
     *
     * @param $key
     *
     * @return mixed
     */
    protected function formattedOrDefault($key)
    {
        $request = request();

        return $request->has("$key.formatted") ? $request->input("$key.formatted") : $request->input($key);
    }

}
