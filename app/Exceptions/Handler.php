<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });


        $this->renderable(function (Throwable $exception, $request) {
            $response = [
                'message' => Lang::get('errors.err500'),
                'status' => 500
            ];

            if ($request->is('api/*')) {
                if ($exception instanceof AuthenticationException) {
                    $response['status'] = 401;
                }

                if (
                    $exception instanceof AuthorizationException ||
                    $exception instanceof AccessDeniedHttpException
                ) {
                    $response['status'] = 403;
                }

                if ($exception instanceof FileNotFoundException) {
                    $response['status'] = 404;
                }

                if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                    $response['status'] = 404;
                }

                if ($exception instanceof ValidationException) {
                    $response['status'] = 400;
                }

//                if($exception instanceof HttpException) {
//                    $response['status'] = 503;
//                }

                $response['message'] = config('app.debug')
                    ? (empty($exception->getMessage()) ? Lang::get('errors.err' . $response['status']) : $exception->getMessage())
                    : Lang::get('errors.err' . $response['status']);

                if ($exception instanceof ValidationException) {
                    $response['message'] = $exception->validator->messages()->first() ?: $response['message'];
                }


                if($exception instanceof ErrorException) {
                    $response['status'] = $exception->getCode() ?? 500;
                    $response['message'] = $exception->getMessage();
                }

                return response()->json($response, $response['status'], ['Content-Type' => 'application/json;charset=utf8'], JSON_UNESCAPED_UNICODE);
            }
        });
    }
}
