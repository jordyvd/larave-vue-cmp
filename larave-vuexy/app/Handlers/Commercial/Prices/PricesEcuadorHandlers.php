<?php

namespace App\Handlers\Commercial\Prices;

class PricesEcuadorHandlers
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
            // [
            //     'title' => 'FAMILIA',
            //     'key' => 'FAMILIA',
            //
            // ],
            [
                'title' => 'SUBFAMILIA',
                'key' => 'SUBFAMILIA',
            ],
            [
                'title' => 'PRECIO',
                'key' => 'PRECIO',
            ],
            [
                'title' => 'UNIDAD',
                'key' => 'UNIDAD',
            ],
            [
                'title' => 'PRINCIPAL',
                'key' => 'PRINCIPAL',
            ],
            [
                'title' => 'CONSIGNACION',
                'key' => 'CONSIGNACION',
            ],
            [
                'title' => 'LIQUIDACION',
                'key' => 'LIQUIDACION',
            ],
            [
                'title' => 'TRANSITO',
                'key' => 'TRANSITO',
            ]
        ];

        return $rows;
    }
}
