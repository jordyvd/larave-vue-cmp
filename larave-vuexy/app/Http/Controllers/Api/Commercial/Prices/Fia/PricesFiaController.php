<?php

namespace App\Http\Controllers\Api\Commercial\Prices\Fia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\Commercial\Prices\Commons\GetDto;
use App\Services\Commercial\Prices\PricesFiaServices;
use App\Services\Commercial\Prices\PricesCommonsServices;
use App\Exports\Commercial\ExportPrices;
use Maatwebsite\Excel\Facades\Excel;
use App\Trait\Commercial\TraitCommercial;

class PricesFiaController extends Controller
{
    use TraitCommercial;
    protected $pricesFiaServices;
    protected $pricesCommonsServices;
    protected $section = 'FIA';
    public function __construct()
    {
        $this->pricesFiaServices = new PricesFiaServices();
        $this->pricesCommonsServices = new PricesCommonsServices($this->section);
    }

    public function get(Request $request)
    {
        $dto = new GetDto($request->all(), $this->section);
        $data = $this->pricesFiaServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headers = $this->setHeadersTrait($headers, $dto->optionLpPricesSelected, $listPricesOptions);
        $items = $data->items();
        $this->mergeDataWithHeadersTrait($headers, $items);
        $response = [
            'data' => $data,
            'headers' => $headers,
            'listPricesOptions' => $listPricesOptions
        ];
        return $this->response($response, 'Listado de precios', 200);
    }

    public function exportToExcel(Request $request)
    {
        $dto = new GetDto($request->all(), $this->section);
        $data = $this->pricesFiaServices->get($dto);
        $headers = $this->pricesCommonsServices->getListPrices();
        $listPricesOptions = $this->pricesCommonsServices->getListPricesOptions();
        $headers = $this->setHeadersTrait($headers, $dto->optionLpPricesSelected, $listPricesOptions);
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
