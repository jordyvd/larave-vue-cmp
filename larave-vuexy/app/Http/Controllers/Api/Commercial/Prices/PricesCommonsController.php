<?php

namespace App\Http\Controllers\Api\Commercial\Prices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Commercial\Prices\PricesCommonsServices;

class PricesCommonsController extends Controller
{
    protected $pricesCommonsServices;

    public function __construct()
    {
        $this->pricesCommonsServices = new PricesCommonsServices();
    }

    public function refreshData()
    {
        $this->pricesCommonsServices->refreshData();
        return $this->response([], 'Datos actualizados', 200);
    }

    public function getLastRefresh()
    {
        $data = $this->pricesCommonsServices->getLastRefresh();
        return $this->response($data, 'Última actualización', 200);
    }

    public function getOptionListPricesSelectedByUser(Request $request)
    {
        $data = $this->pricesCommonsServices->getOptionListPricesSelectedByUser($request->company);
        return $this->response($data, 'Opción seleccionada por el usuario', 200);
    }

    public function storeOptionListPricesSelectedByUser(Request $request)
    {
        $this->pricesCommonsServices->storeOptionListPricesSelectedByUser($request->company, $request->option_selected);
        return $this->response([], 'Opción seleccionada por el usuario guardada', 200);
    }
}
