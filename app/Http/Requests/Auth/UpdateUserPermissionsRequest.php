<?php

namespace App\Http\Requests\Auth;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

/**
 * @property array|RoleEnum[] $roles
 * @property array|PermissionEnum[] $permissions
 */
class UpdateUserPermissionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                new Enum(RoleEnum::class),
                Rule::in(collect(RoleEnum::adminnableRoles())->map(fn (RoleEnum $enum) => $enum->value)),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                new Enum(PermissionEnum::class),
                Rule::in(collect(PermissionEnum::adminnablePermissions())->map(fn (PermissionEnum $enum) => $enum->value)),
            ],
        ];
    }
}
