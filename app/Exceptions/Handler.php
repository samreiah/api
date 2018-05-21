<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Guzzle\Http\Exception\ClientErrorResponseException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
		if($e instanceof HttpException) {
            if(503 == $e->getStatusCode()){
                return $this->renderException('MAINTENANCE_MODE', 'The API service is down for maintenance', 503);
            }
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->renderException('ENDPOINT_NOTFOUND', 'Request end point not found', 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->renderException('METHOD_NOTALLOWED', 'Request method not allowed', 404);
        }

        if ($e instanceof ClientErrorResponseException) {
            return $this->renderException('TOKBOX_FORBIDDEN', $e->getMessage(), 403);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->renderException('ID_NOTFOUND', 'The given ID not found', 404);
        }
		
        return parent::render($request, $e);
    }
	
	
	/**
     * Render an json exception into an HTTP response.
     *
     * @param  string  $code
     * @param  string  $message
     * @param  integer  $status_code
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    private function renderException($code, $message, $status_code, $headers = [])
    {
        $data = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ]
        ];

        return response()->json($data, $status_code, $headers);
    }
}
