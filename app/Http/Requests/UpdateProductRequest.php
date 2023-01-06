<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @property string $name
 * @property string $sku
 * @property string $description
 * @property UploadedFile|null $image
 */
class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('products')->ignore($this->route('product'))->whereNull('deleted_at'),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:10000'],
        ];
    }
}
