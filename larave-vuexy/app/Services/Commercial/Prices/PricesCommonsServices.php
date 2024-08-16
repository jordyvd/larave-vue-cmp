<?php

namespace App\Services\Commercial\Prices;

use Illuminate\Support\Facades\DB;

class PricesCommonsServices
{
    protected $company;
    public function __construct($company = null)
    {
        $this->company = $company; // EXAMPLE: FIA, INF
    }
    public function refreshData()
    {
        DB::statement('EXEC sp_ejecutar_Job_Stock');
    }
    public function getLastRefresh()
    {
        $data = DB::table('vActJob')->where('JobName', 'ETL-STOCK')->first();
        return isset($data->ExecutedAt) ? $data->ExecutedAt : null;
    }

    public function getListPrices()
    {
        $data = DB::table('vtit_listaprecios as vap')
            ->select('vap.id', 'vap.titulo as title', DB::raw('CONCAT(\'[\', STRING_AGG(lpo.[option], \', \'), \']\') as options'))
            ->leftJoin('list_price_options as lpo', function ($join) {
                $join->on('vap.id', '=', 'lpo.list_price_id')
                    ->on('vap.EMP', '=', 'lpo.company');
            })
            ->where('vap.EMP', $this->company)
            ->where('vap.estado', 'S')
            ->groupBy('vap.id', 'vap.titulo')
            ->get();

        foreach ($data as $item) {
            $item->options = json_decode($item->options);
        }
        return $data;
    }

    public function getListPricesOptions()
    {
        return DB::table('list_price_options')->select('id', 'list_price_id', 'option')->where('company', $this->company)->get();
    }

    public function storeListPricesPublic($prices)
    {
        foreach ($prices as $price) {
            DB::table('list_price_options')->insert(
                [
                    'list_price_id' => $price['id'],
                    'company' => $this->company,
                    'option' => $price['option'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }

    public function deleteListPricesPublic()
    {
        DB::table('list_price_options')
            ->where('company', $this->company)
            ->delete();
    }

    public function getOptionListPricesSelectedByUser($company)
    {
        $data = DB::table('list_price_option_selected_by_user')
            ->where('user_id', auth()->user()->id)
            ->where('company', strtoupper($company))
            ->first();

        return isset($data->option_selected) ? $data->option_selected : 0;
    }

    public function storeOptionListPricesSelectedByUser($company, $option_selected)
    {
        DB::table('list_price_option_selected_by_user')
            ->updateOrInsert(
                [
                    'user_id' => auth()->user()->id,
                    'company' => strtoupper($company)
                ],
                [
                    'option_selected' => $option_selected,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
    }
}
