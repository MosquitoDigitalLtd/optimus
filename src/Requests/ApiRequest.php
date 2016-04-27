<?php

namespace Appitized\Optimus\Requests;

use App\Http\Requests\Request;
use Appitized\Optimus\Exceptions\ApiValidationException;
use Illuminate\Http\Exception\HttpResponseException;

class ApiRequest extends Request
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
