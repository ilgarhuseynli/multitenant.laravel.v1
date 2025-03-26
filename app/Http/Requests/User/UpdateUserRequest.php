<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        $user = $this->route('user');

        if (!Gate::allows('user_edit', $user)) {
            return false;
        }

        return true;
    }

    public function rules()
    {
        $id = request()->route('user')->id ?? request()->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id .',id'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $id .',id'],
        ];

    }

}
