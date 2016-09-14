<?php

namespace Appitized\Optimus\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Appitized\Optimus\Exceptions\ApiValidationException;

class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            throw new ApiValidationException($validator->getMessageBag()
              ->toArray(), 422);
        }

        throw new HttpResponseException($this->response(
          $this->formatErrors($validator)
        ));
    }
}
