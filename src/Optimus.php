<?php

namespace Appitized\Optimus;

use Appitized\Optimus\Exceptions\InvalidApiTransformation;
use Appitized\Optimus\Exceptions\InvalidTransformer;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class Optimus
{
    protected $manager;
    protected $dataType;
    protected $data;
    protected $transformer;
    protected $resourceName;
    protected $includes = [];
    protected $paginator;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function withMessage($message, $statusCode = 200)
    {
        $success = ['status' => $statusCode, 'messages' => [$message]];
        return new JsonResponse(['success' => $success], $statusCode);
    }

    public function withError($message, $statusCode = 400)
    {
        $success = ['status' => $statusCode, 'messages' => [$message]];
        return new JsonResponse(['error' => $success], $statusCode);
    }

    public function withCollection($data, $transfomer = null, $resourceName = null)
    {
        $this->resourceName = $resourceName;

        return $this->data('collection', $data, $transfomer);
    }

    public function withItem($data, $transformer = null, $resourceName = null)
    {
        $this->resourceName = $resourceName;

        return $this->data('item', $data, $transformer);
    }

    public function withPaginatedCollection(Paginator $paginator, $transformer = null, $resourceName = null)
    {
        $this->resourceName = $resourceName;
        $this->paginator = $paginator;

        return $this->data('collection', $paginator->getCollection(), $transformer);
    }

    public function transformWith($transformer)
    {
        if (!class_exists($transformer)) {
            throw new InvalidTransformer();
        }
        $this->transformer = new $transformer;

        return $this;
    }

    protected function data($dataType, $data, $transformer)
    {
        $this->dataType = $dataType;
        $this->data = $data;
        if (!is_null($transformer)) {
            $this->transformer = $transformer;
        }

        return $this;
    }

    public function resourceName($resourceName)
    {
        $this->resourceName = $resourceName;

        return $this;
    }

    public function getResource()
    {
        $resourceClass = 'League\\Fractal\\Resource\\' . ucfirst($this->dataType);
        if (!class_exists($resourceClass)) {
            throw new InvalidApiTransformation();
        }
        $resource = new $resourceClass($this->data, $this->transformer, $this->resourceName);
        if($this->paginator)
        {
            $resource->setPaginator(new IlluminatePaginatorAdapter($this->paginator));
        }

        return $resource;
    }

    public function createData()
    {
        $this->parseIncludes(Input::get('includes'));
        if (!is_null($this->includes)) {
            $this->manager->parseIncludes($this->includes);
        }
        $resource = $this->getResource();

        return $this->manager->createData($resource);
    }

    public function parseIncludes($includes)
    {
        if (is_string($includes)) {
            $includes = array_map(function ($value) {
                return trim($value);
            },  explode(',', $includes));
        }

        $this->includes = array_merge($this->includes, (array)$includes);
        return $this;
    }

    public function __call($name, array $arguments)
    {
        if (!starts_with($name, 'include')) {
            trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
        }
        $includeName = lcfirst(substr($name, strlen('include')));

        return $this->parseIncludes($includeName);
    }

    protected function transform($conversionMethod)
    {
        $fractalData = $this->createData();

        return $fractalData->$conversionMethod();
    }

    public function toArray($statusCode = 200)
    {
        return Response::make($this->transform('toArray'), $statusCode);
    }

    public function toJson($statusCode = 200)
    {
        return Response::make($this->transform('toJson'), $statusCode);
    }
}
