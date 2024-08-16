<?php

namespace App\Services\Commercial\Prices;

use Illuminate\Support\Facades\DB;
use App\DTO\Commercial\Prices\Ecuador\GetDto;

class PricesEcuadorServices
{
    public function get(GetDto $dto)
    {
        $query = DB::table('listar_precio_ec')->select('*');

        if (isset($dto->search)) {
            $search = $dto->search;
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(CODART) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(ARTICULO) LIKE ?', ['%' . strtolower($search) . '%'])
                    // ->orWhereRaw('LOWER(FAMILIA) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(SUBFAMILIA) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(MARCA) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(PRECIO) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(UNIDAD) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $query->orderBy('SUBFAMILIA', 'asc')
            ->orderBy('ARTICULO', 'asc');

        if (isset($dto->page) && isset($dto->perPage)) {
            return $query->paginate($dto->perPage, $dto->page);
        }

        return $query->get();
    }
}
