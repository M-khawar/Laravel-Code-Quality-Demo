<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handleException($e)
    {
        $class_name = get_class($e);

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            //above we can also check $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
            return response()->error($e->getMessage(), $e->getStatusCode());

        } else if ($class_name == "Illuminate\Validation\ValidationException") {
            return response()->error($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY, ["errors" => $e->errors()]);

        } else if ($class_name == \RuntimeException::class) {
            return response()->error($e->getMessage(), Response::HTTP_NOT_ACCEPTABLE);

        }else if ($e instanceof ModelNotFoundException) {
            return response()->error("No record exist against against your requested id.", Response::HTTP_NOT_FOUND);

        } else {
            return response()->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
