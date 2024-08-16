<?php

namespace App\Services\Commercial;

use Illuminate\Support\Facades\DB;

class CommercialConfigServices
{
    public function getCompanies()
    {
        return DB::table('vtit_listaprecios')
            ->select('EMP')
            ->groupBy('EMP')
            ->get();
    }

    public function getLisPrices($company)
    {
        return DB::table('vtit_listaprecios')
            ->select('id', 'titulo as title')
            ->where('EMP', $company)
            ->where('estado', 'S')
            ->get();
    }

    public function getUserListPrices($userId, $company)
    {
        return DB::table('user_list_prices')
            ->select('id', 'company', 'list_price_id')
            ->where('user_id', $userId)
            ->where('company', $company)
            ->get();
    }
}
