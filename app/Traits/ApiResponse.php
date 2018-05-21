<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Default http status code.
     *
     * @var integer $statusCode
     */
    protected $statusCode = 200;

    /**
     * Get the http status code.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the http status code.
     *
     * @return object $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($code, $message = '', $data = [])
    {
        $response = [
            'status'  => 'error',
            'code'    => $code,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $this->respond($response);
    }

    public function respondWithSuccess($code, $message = '', $data = [])
    {

      $response = [
          'status'  => 'success',
          'code'    => $code,
          'message' => $message
      ];

      if (!empty($data)) {
          $response['data'] = isset($data['data']) ? $data['data'] : $data;
      }

      return $this->respond($response);
    }

    public function respondBadRequest($message)
    {
        //Get the first error message from validator error array
        if(is_array($message)) {
            $message = current($message);
            $message = is_array($message) ? current($message) : $message;
        }

        return $this->setStatusCode(400)->respondWithError('INVALID_REQUEST', $message);
    }

    public function respondNotFound($code, $message = 'Not found')
    {
        return $this->setStatusCode(404)
                  ->respondWithError($code, $message);
    }

    public function respondInternalError($code = 'INTERNAL_SERVER_ERROR', $message = 'Internal server error occured')
    {
        return $this->setStatusCode(500)
                  ->respondWithError($code, $message);
    }

}
