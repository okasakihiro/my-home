<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //判断是否存是HTTP异常
        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
                //404异常
                case 404:
                    return response([
                        'code' => 404,
                        'message' => 'Not Found'
                    ]);
                    break;
                    //405异常
                case 405:
                    return response([
                        'code' => 405,
                        'message' => 'Method not allowed'
                    ]);
                    break;
                    //其他异常
                default:
                    return response([
                        'code' => 500,
                        'message' => 'Server Error'
                    ]);
            }
        }
        return parent::render($request, $exception);
    }
}
