<?php

namespace App\Http\Controllers\Api\Commercial\Prices\Infrima;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Commercial\Prices\PricesInfrimaServices;
use App\Services\Commercial\Prices\PricesCommonsServices;
use App\Handlers\Commercial\Prices\PricesInfrimaHandlers;
use App\DTO\Commercial\Prices\Commons\GetDto;
use App\Exports\Commercial\ExportPrices;
use Maatwebsite\Excel\Facades\Excel;
use App\Trait\Commercial\TraitCommercial;

class PricesInfrimaController extends Controller
{
    use TraitCommercial;
    protected $pricesInfrimaServices;
    protected $pricesCommonsServices;
    protected $pricesInfrimaHandlers;

    protected $section = 'INF';

    public function __construct()
    {
        $this->pricesInfrimaServices = new PricesInfrimaServices();
        $this->pricesCommonsServices = new PricesCommonsServices($this->section);
        $this->pricesInfrimaHandlers = new PricesInfrimaHandlers();
    }

    public function get(Request $request)
    {
        $dto = new GetDto($request->all(), $this->section);
        $data = $this->pricesInfrimaServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headersDefault = $this->pricesInfrimaHandlers->defineDefaultHeadersDefaults();
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
        $data = $this->pricesInfrimaServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headersDefault = $this->pricesInfrimaHandlers->defineDefaultHeadersDefaults();
        $headers = $this->setHeadersTrait($headers, $dto->optionLpPricesSelected, $listPricesOptions, $headersDefault);
        return Excel::download(new ExportPrices($headers, $data), 'precios-fia-' . now() . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
