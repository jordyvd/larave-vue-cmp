<?php

namespace App\Handlers\Commercial\Prices;

class PricesQuoHandlers
{
    public function defineDefaultHeadersDefaults()
    {
        $rows = [
            [
                'title' => 'CODIGO',
                'key' => 'CODART',
            ],
            [
                'title' => 'ARTICULO',
                'key' => 'ARTICULO',
            ],
            [
                'title' => 'SUBFAMILIA',
                'key' => 'SUBFAMILIA',
            ],
            [
                'title' => 'MARCA',
                'key' => 'MARCA',
            ],
            [
                'title' => 'UND',
                'key' => 'UND',
            ],
            [
                'title' => 'SURCO',
                'key' => 'SURCO',
            ],
            [
                'title' => 'VILLA',
                'key' => 'VILLA',
            ],
            [
                'title' => 'CONSIGNACION',
                'key' => 'CONSIGNACION',
            ],
            [
                'title' => 'TRANSITO',
                'key' => 'TRANSITO',
            ]
        ];

        return $rows;
    }
}
