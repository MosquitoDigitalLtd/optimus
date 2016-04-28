<?php

namespace Appitized\Optimus\Traits;

use Appitized\Optimus\Exceptions\ApiException;
use Appitized\Optimus\Exceptions\ApiValidationException;
use Appitized\Optimus\Exceptions\OptimusException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use ReflectionClass;

trait HandlesApiExceptions
{

    public function wasApiExceptionThrown(Exception $e)
    {
        return ($e instanceof OptimusException);
    }

    public function renderApiException($request, Exception $e)
    {
        if ($e instanceof ApiException) {
            if (!$request->wantsJson()) {
                return abort($e->getCode());
            }
            return $e->display();
        }
        if ($e instanceof ApiValidationException) {
            if (!$request->wantsJson()) {
                return abort($e->getCode());
            }
            return $e->display();
        }
        if ($e instanceof ModelNotFoundException && $request->wantsJson()) {
            $model = new ReflectionClass($e->getModel());
            throw new ApiException('No results have been found for the requested resource ' . $model->getShortName(), 404);
        }
    }
}
