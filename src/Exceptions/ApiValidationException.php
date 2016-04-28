<?php

namespace Appitized\Optimus\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use ReflectionClass;

class ApiValidationException extends Exception implements OptimusException
{

    protected $errors;

    public function __construct($errors, $statusCode)
    {
        $this->errors = $errors;
        parent::__construct('Validation failed', $statusCode);
    }

    /**
     * Display the api exception to the user as JSON
     *
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function display(array $headers = [])
    {
        $error = [
          'status' => $this->getStatusCode(),
          'title' => 'Validation failed',
          'messages' => $this->getErrors(),
        ];

        return new JsonResponse(['errors' => [$error]], $this->getCode(),
          array_merge($headers, ['Content-Type' => $this->contentType()]));
    }

    /**
     * Return the short name of the called class
     *
     * @return string
     */
    public function getShortName()
    {
        $class = new ReflectionClass(get_called_class());

        return $class->getShortName();
    }

    protected function getStatusCode()
    {
        return $this->code;
    }

    protected function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get the supported content type.
     *
     * @return string
     */
    public function contentType()
    {
        return 'application/json';
    }

}
