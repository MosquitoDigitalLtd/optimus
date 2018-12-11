<?php

namespace Appitized\Optimus\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Appitized\Optimus\Exceptions\ApiValidationException;

class ApiRequest extends FormRequest
{
    public function onlyWith(array $keys)
    {
        return array_reduce($this->only($keys), 'array_merge', array());
    }
    
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            throw new ApiValidationException($validator->getMessageBag()
              ->toArray(), 400);
        }

        throw new HttpResponseException($this->response(
          $this->formatErrors($validator)
        ));
    }
}
