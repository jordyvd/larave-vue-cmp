<?php

namespace App\Handlers\Commercial\Prices;

class PricesAtqHandlers
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
                'title' => 'UND',
                'key' => 'UND',
            ],
            [
                'title' => 'APLICACION',
                'key' => 'APLICACION',
                'default' => true
            ],
            [
                'title' => 'PRINCIPAL',
                'key' => 'PRINCIPAL',
            ],
            [
                'title' => 'BREÑA',
                'key' => 'BREÑA',
            ],
            [
                'title' => 'MERCADERIA',
                'key' => 'MERCADERIA',
            ],
            [
                'title' => 'TRANSITO',
                'key' => 'TRANSITO',
            ]
        ];

        return $rows;
    }
}
