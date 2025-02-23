<?php

namespace App\Exceptions;

//use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * A list of the exception types that should not be reported.
     *
     * @var array
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    //ublic function report(Throwable $exception);
    /*public function report(Exception $exception)
    {
        parent::report($exception);
    }*/

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }
    /*
    public function report(Exception $exception)
    {
        parent::report($exception);
    }
    */

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    /*
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }*/

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

//Illuminate\\View\\ViewException
        if ($exception instanceof ViewException) {
            return response()->view('errors.503', [], 503);
        }


        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
