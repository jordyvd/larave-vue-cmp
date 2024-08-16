<?php

namespace App\DTO\Commercial\Prices\Commons;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\Commercial\Prices\PricesCommonsServices;

class GetDto
{
    public string|null $search;
    public int $optionLpPricesSelected;
    public int|null $page;
    public int|null $perPage;
    public function __construct($request, $company)
    {
        $this->validate($request);

        $service = new PricesCommonsServices();

        $optionLpPricesSelected = $service->getOptionListPricesSelectedByUser($company);

        $this->search = $request['search'] ?? null;
        $this->optionLpPricesSelected = $optionLpPricesSelected;
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
