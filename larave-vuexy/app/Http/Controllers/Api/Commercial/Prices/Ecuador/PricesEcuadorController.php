<?php

namespace App\Http\Controllers\Api\Commercial\Prices\Ecuador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Commercial\Prices\PricesEcuadorServices;
use App\DTO\Commercial\Prices\Ecuador\GetDto;
use App\Handlers\Commercial\Prices\PricesEcuadorHandlers;
use App\Trait\Commercial\TraitCommercial;
use App\Exports\Commercial\ExportPrices;
use Maatwebsite\Excel\Facades\Excel;

class PricesEcuadorController extends Controller
{
    use TraitCommercial;
    protected $pricesEcuadorServices;
    protected $pricesEcuadorHandlers;

    public function __construct()
    {
        $this->pricesEcuadorServices = new PricesEcuadorServices();
        $this->pricesEcuadorHandlers = new PricesEcuadorHandlers();
    }

    public function get(Request $request)
    {
        $getDto = new GetDto($request->all());
        $data = $this->pricesEcuadorServices->get($getDto);
        $headers = $this->pricesEcuadorHandlers->defineDefaultHeadersDefaults();
        $items = $data->items();
        $this->mergeDataWithHeadersTrait($headers, $items);
        $response = [
            'data' => $data,
            'headers' => $headers
        ];
        return $this->response($response, "Data retrieved successfully", 200);
    }

    public function exportToExcel(Request $request)
    {
        $dto = new GetDto($request->all());
        $data = $this->pricesEcuadorServices->get($dto);
        $headers = $this->pricesEcuadorHandlers->defineDefaultHeadersDefaults();
        $this->mergeDataWithHeadersTrait($headers, $data);
        return Excel::download(new ExportPrices($headers, $data), 'precios-ecuador-' . now() . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
