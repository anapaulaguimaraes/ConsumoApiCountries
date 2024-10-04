<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Este service é responsável por interagir com a API de países e obter informações através de requisições HTTP.
 */
class ApiService
{
    public function getCountries()
    {
        $response = Http::withOptions(['verify' => false])->get('https://restcountries.com/v3.1/all');
        
        if ($response->successful()) {
            return $response->json();
        } else {
            return [
                'error' => 'Erro ao obter países: ' . $response->status(),
            ];
        }
    }
}