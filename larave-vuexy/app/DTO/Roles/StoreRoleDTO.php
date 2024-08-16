<?php

namespace App\DTO\Roles;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreRoleDTO
{
    public string $name;
    public string|null $description;
    public function __construct(
        $request
    ) {
        $this->validate($request);

        $this->name = $request['name'];
        $this->description = $request['description'];
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'name' => 'required|string',
            'description' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
