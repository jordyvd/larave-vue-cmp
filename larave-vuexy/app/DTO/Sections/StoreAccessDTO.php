<?php

namespace App\DTO\Sections;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreAccessDTO
{
    public array $access;
    public int $role_id;
    public function __construct($request)
    {
        $this->validate($request);
        $this->access = $request['access'];
        $this->role_id = $request['role_id'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'access.*.action_id' => 'required|integer',
            'access.*.section_id' => 'required|integer',
            'access.*.access' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
