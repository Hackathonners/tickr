<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Fractal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class ApiController.
 */
class ApiController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = HttpResponse::HTTP_OK;

    /**
     *
     */
    const CODE_WRONG_ARGS = 'ERR-WRONGARGS';
    /**
     *
     */
    const CODE_NOT_FOUND = 'ERR-NOTFOUND';
    /**
     *
     */
    const CODE_INTERNAL_ERROR = 'ERR-WHOOPS';
    /**
     *
     */
    const CODE_UNAUTHORIZED = 'ERR-UNAUTHORIZED';
    /**
     *
     */
    const CODE_FORBIDDEN = 'ERR-FORBIDDEN';

    /**
     * Getter for statusCode.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode.
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Respond with the given data after checking
     * that everything exists and is valid.
     *
     * @param $data
     * @param $callback
     * @param array $includes
     *
     * @return \Response
     */
    public function respondWith($data, $callback, $includes = [])
    {
        //if $data is null throw error
        if (!$data) {
            return $this->errorNotFound('Requested response not found.');
        }
        //if $data is a Collection or a Paginated Collection
        elseif ($data instanceof Collection || $data instanceof LengthAwarePaginator) {
            return $this->respondWithCollection($data, $callback, $includes);
        }
        //if $data is an Item
        elseif ($data instanceof Model) {
            return $this->respondWithItem($data, $callback, $includes);
        } else {
            return $this->errorInternalError();
        }
    }

    /**
     * @param $item
     * @param $callback
     * @param array $includes
     * @return mixed
     */
    protected function respondWithItem($item, $callback, $includes = [])
    {
        return Fractal::includes($includes)->item($item, $callback)->toJson();
    }

    /**
     * @param $collection
     * @param $callback
     * @param array $includes
     * @return mixed
     */
    protected function respondWithCollection($collection, $callback, $includes = [])
    {
        $result = Fractal::includes($includes)->collection($collection, $callback);

        if ($collection instanceof LengthAwarePaginator) {
            $result->paginateWith(new IlluminatePaginatorAdapter($collection));
        }

        return $result->toJson();
    }

    /**
     * @param array $array
     * @param array $headers
     * @return mixed
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        $response = Response::json($array, $this->statusCode, $headers);

        return $response;
    }

    /**
     * @param $message
     * @param $errorCode
     * @return mixed
     */
    public function respondWithError($message, $errorCode)
    {
        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ],
        ]);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)
            ->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
            ->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(HttpResponse::HTTP_UNAUTHORIZED)
            ->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(HttpResponse::HTTP_BAD_REQUEST)
            ->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new HttpResponseException(
                $this->errorWrongArgs($validator->errors()->first())
            );
        }
    }
}
