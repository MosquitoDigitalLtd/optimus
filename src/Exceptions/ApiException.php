<?php

namespace Appitized\Optimus\Exceptions;

use Illuminate\Http\JsonResponse;
use Exception;

class ApiException extends Exception implements OptimusException
{

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
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
          'status' => $this->getCode(),
          'title' => $this->getShortName(),
          'messages' => [$this->getMessage()],
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
        $class = new \ReflectionClass(get_called_class());

        return $class->getShortName();
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
