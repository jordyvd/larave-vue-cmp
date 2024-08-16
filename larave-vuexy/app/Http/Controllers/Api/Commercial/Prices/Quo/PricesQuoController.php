<?php

namespace App\Http\Controllers\Api\Commercial\Prices\Quo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\Commercial\Prices\Commons\GetDto;
use App\Services\Commercial\Prices\PricesQuoServices;
use App\Services\Commercial\Prices\PricesCommonsServices;
use App\Handlers\Commercial\Prices\PricesQuoHandlers;
use App\Trait\Commercial\TraitCommercial;
use App\Exports\Commercial\ExportPrices;
use Maatwebsite\Excel\Facades\Excel;

class PricesQuoController extends Controller
{
    use TraitCommercial;
    protected $pricesQuoServices;
    protected $pricesCommonsServices;
    protected $pricesQuoHandlers;
    protected $section = 'QUO';

    public function __construct()
    {
        $this->pricesQuoServices = new PricesQuoServices();
        $this->pricesCommonsServices = new PricesCommonsServices($this->section);
        $this->pricesQuoHandlers = new PricesQuoHandlers();
    }

    public function get(Request $request)
    {
        $dto = new GetDto($request->all(), $this->section);
        $data = $this->pricesQuoServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices() ?? [];
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headersDefault = $this->pricesQuoHandlers->defineDefaultHeadersDefaults();
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
        $data = $this->pricesQuoServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headersDefault = $this->pricesQuoHandlers->defineDefaultHeadersDefaults();
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
