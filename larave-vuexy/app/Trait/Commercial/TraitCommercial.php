<?php

namespace App\Trait\Commercial;

trait TraitCommercial
{
    public function setHeadersTrait($headers, $optionLpPricesSelected, $listPricesOptions, $headersDefaults = null)
    {
        $headersDefaultsLocal = [];

        if ($headersDefaults == null) {
            $headersDefaultsLocal = $this->defineDefaultHeadersDefaultsTrait();
        } else {
            $headersDefaultsLocal = $headersDefaults;
        }

        if ($optionLpPricesSelected == 3) {
            foreach ($headers as $header) {
                $headersDefaultsLocal[] = [
                    'title' => $this->replaceTextOfHeader($header->title),
                    'key' => $header->title,
                ];
            }
        } else {
            foreach ($listPricesOptions as $option) {
                foreach ($headers as $header) {
                    if ($option->list_price_id == $header->id && in_array($optionLpPricesSelected, $header->options) && $option->option == $optionLpPricesSelected) {
                        $headersDefaultsLocal[] = [
                            'title' => $this->replaceTextOfHeader($header->title),
                            'key' => $header->title
                        ];
                    }
                }
            }
        }

        return $headersDefaultsLocal;
    }

    public function replaceTextOfHeader($title)
    {
        $title = str_replace('LISTA DE PRECIOS', 'LP.', $title);
        $title = str_replace('LISTA DE PRECIO', 'LP.', $title);
        $title = str_replace('LISTA PRECIOS', 'LP.', $title);
        $title = str_replace('LISTA PRECIO', 'LP.', $title);
        $title = str_replace('LISTA DE', 'LP.', $title);
        $title = str_replace('LISTA', 'LP.', $title);
        $title = str_replace(' ', '.', $title);
        $title = str_replace('..', '.', $title);
        return $title;
    }

    public function mergeDataWithHeadersTrait($headers, $data)
    {
        foreach ($data as $value) {
            foreach ($headers as $header) {
                if (is_numeric($value->{$header['key']}) && $header['title'] != 'CODIGO') {

                    $value->{$header['key']} = number_format($value->{$header['key']}, 2);

                } else {
                    $value->{$header['key']} = trim($value->{$header['key']});
                }
            }
        }
    }

    public function defineDefaultHeadersDefaultsTrait()
    {
        $rows = [
            [
                'title' => 'CODIGO',
                'key' => 'CODART',
                'default' => true
            ],
            [
                'title' => 'ARTICULO',
                'key' => 'ARTICULO',
                'default' => true
            ],
            [
                'title' => 'SUBFAMILIA',
                'key' => 'SUBFAMILIA',
                'default' => true
            ],

            [
                'title' => 'SUBFAMILIA2',
                'key' => 'SUBFAMILIA2',
                'default' => true
            ],
            [
                'title' => 'DESCRIPCION',
                'key' => 'DESCRI2',
                'default' => true
            ],
            [
                'title' => 'UND',
                'key' => 'UND',
                'default' => true
            ],
            [
                'title' => 'APLICACION',
                'key' => 'APLICACION',
                'default' => true
            ],
            [
                'title' => 'PRINCIPAL',
                'key' => 'PRINCIPAL',
                'default' => true
            ],
            [
                'title' => 'BREÑA',
                'key' => 'BREÑA',
                'default' => true
            ],
            [
                'title' => 'MERCADERIA',
                'key' => 'MERCADERIA',
                'default' => true
            ],
            [
                'title' => 'TRANSITO',
                'key' => 'TRANSITO',
                'default' => true
            ]
        ];

        return $rows;
    }
}
