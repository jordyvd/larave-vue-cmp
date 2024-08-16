<?php

namespace App\DTO\Commercial\Prices\Ecuador;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetDto
{
    public string|null $search;
    public int|null $page;
    public int|null $perPage;
    public function __construct($request)
    {
        $this->validate($request);

        $this->search = $request['search'] ?? null;
        $this->page = $request['page'] ?? null;
        $this->perPage = $request['perPage'] ?? null;
    }

    private function validate(array $request): void
    {
        $validator = Validator::make($request, [
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'perPage' => 'integer|nullable',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            throw new ValidationException($validator, response()->json($errors, 422));
        }
    }
}
