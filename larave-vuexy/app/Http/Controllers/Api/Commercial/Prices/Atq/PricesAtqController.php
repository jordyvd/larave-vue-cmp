<?php

namespace App\Http\Controllers\Api\Commercial\Prices\Atq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\Commercial\Prices\Commons\GetDto;
use App\Services\Commercial\Prices\PricesAtqServices;
use App\Services\Commercial\Prices\PricesCommonsServices;
use App\Handlers\Commercial\Prices\PricesAtqHandlers;
use App\Exports\Commercial\ExportPrices;
use Maatwebsite\Excel\Facades\Excel;
use App\Trait\Commercial\TraitCommercial;

class PricesAtqController extends Controller
{
    use TraitCommercial;
    protected $pricesAtqServices;
    protected $pricesCommonsServices;
    protected $pricesAtqHandlers;
    protected $section = 'ATQ';

    public function __construct()
    {
        $this->pricesAtqServices = new PricesAtqServices();
        $this->pricesCommonsServices = new PricesCommonsServices($this->section);
        $this->pricesAtqHandlers = new PricesAtqHandlers();
    }

    public function get(Request $request)
    {
        $dto = new GetDto($request->all(), $this->section);
        $data = $this->pricesAtqServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headersDefault = $this->pricesAtqHandlers->defineDefaultHeadersDefaults();
        $headers = $this->setHeadersTrait($headers, $dto->optionLpPricesSelected, $listPricesOptions, $headersDefault);
        $items = $data->items();
        $this->mergeDataWithHeadersTrait($headers, $items);
        $response = [
            'data' => $data,
            'headers' => $headers,
            'listPricesOptions' => $listPricesOptions
        ];
        return $this->response($response, "Data retrieved successfully", 200);
    }

    public function exportToExcel(Request $request)
    {
        $dto = new GetDto($request->all(), $this->section);
        $data = $this->pricesAtqServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headersDefault = $this->pricesAtqHandlers->defineDefaultHeadersDefaults();
        $headers = $this->setHeadersTrait($headers, $dto->optionLpPricesSelected, $listPricesOptions, $headersDefault);
        return Excel::download(new ExportPrices($headers, $data), 'precios-atq-' . now() . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function getListPrices()
    {
        $data = $this->pricesCommonsServices->getListPrices();
        return $this->response($data, 'Listado de precios', 200);
    }

    public function storeListPricesPublic(Request $request)
    {
        try {
            $this->pricesCommonsServices->deleteListPricesPublic();
            $this->pricesCommonsServices->storeListPricesPublic($request->listPrices);
            return $this->response([], 'Precios guardados', 200);
        } catch (\Exception $e) {
            return $this->response([], 'Error al guardar precios', 500);
        }
    }
}
