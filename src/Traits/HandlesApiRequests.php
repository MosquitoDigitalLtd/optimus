<?php

namespace Appitized\Optimus\Traits;

use Appitized\Optimus\Exceptions\ApiException;
use Appitized\Optimus\Exceptions\ApiValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use ReflectionClass;

trait HandlesApiRequests
{
    public function renderApiResponse($request, Exception $e)
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
