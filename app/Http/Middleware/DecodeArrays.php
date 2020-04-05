<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class DecodeArrays extends TransformsRequest
{
    /**
     * The attributes that should not be trimmed.
     *
     * @var array
     */
    protected $include = ['provinces', 'countries'];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->include, true)) {
            $value = json_decode(base64_decode($value));
        }

        return $value;
    }
}
