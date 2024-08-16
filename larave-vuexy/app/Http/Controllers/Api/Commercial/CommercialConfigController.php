<?php

namespace App\Http\Controllers\Api\Commercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Commercial\CommercialConfigServices;

class CommercialConfigController extends Controller
{
    protected $commercialConfigServices;
    public function __construct()
    {
        $this->commercialConfigServices = new CommercialConfigServices();
    }

    public function getCompanies()
    {
        $data = $this->commercialConfigServices->getCompanies();
        return $this->response($data, 'Companies', 200);
    }

    public function getLisPrices(Request $request)
    {
        $company = $request->company;
        $data = $this->commercialConfigServices->getLisPrices($company);
        return $this->response($data, 'List prices', 200);
    }

    public function getUserListPrices(Request $request)
    {
        $userId = $request->user()->id;
        $company = $request->company;
        $data = $this->commercialConfigServices->getUserListPrices($userId, $company);
        return $this->response($data, 'List prices', 200);
    }
}
