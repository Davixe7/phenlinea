<?php

namespace App\Services;

class PlatesService
{
    public function fetchPlates()
    {
        //Ultima visita de cada placa - identificada por timestamp
        $visitIds = auth()->user()
            ->visits()->whereNotNull('plate')
            ->groupBy(['plate', 'admin_id'])
            ->selectRaw('MAX(`created_at`) as fecha')
            ->pluck('fecha');

        $visits = auth()->user()->visits()
            ->select(['admin_id', 'plate', 'extension_name'])
            ->selectRaw('CONCAT(`plate`, " ", extension_name, " visitante") as label')
            ->whereIn('created_at', $visitIds)
            ->pluck('label')
            ->toArray();

        $plates = auth()->user()->vehicles()->with('extension')->get();
        $plates = $plates->map(fn($v) => $v->plate . " " . $v->extension->name . " residente")->toArray();
        return array_merge($visits, $plates);
    }
}
