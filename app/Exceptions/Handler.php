<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
     public function register(): void
    {
        // Validation Error
        $this->renderable(function (ValidationException $e, Request $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Please check the input fields.',
                    'errors'  => $e->errors(),
                ], 422);
            }

        });


        // Database Error
        $this->renderable(function (QueryException $e, Request $request) {

            Log::error('Database Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => $request->fullUrl(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'A database error occurred. Please try again.',
                ], 500);
            }

        });


        // 404 Error
        $this->renderable(function (NotFoundHttpException $e, Request $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'The requested data was not found.',
                ], 404);
            }

        });


        // 403 Error
        $this->renderable(function (AccessDeniedHttpException $e, Request $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'You are not authorized to perform this action.',
                ], 403);
            }

        });


        // Other Errors
        $this->renderable(function (Throwable $e, Request $request) {

            Log::error('Application Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => $request->fullUrl(),
                'method'  => $request->method(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }

        });
    }

    public function render($request, Throwable $e) 
    {
        if($e instanceof IntegrityAvailableException){
            return redirect()->back()->with(['error'=>$e->getMessage()]);
        }

        return parent::render($request, $e);
    }
}
