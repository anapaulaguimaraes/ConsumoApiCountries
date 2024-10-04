<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;

/**
 * Este controller é responsável por gerenciar as operações relacionadas aos países.
 */
class CountriesController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index(Request $request)
    {
        try {
            $allCountries = $this->apiService->getCountries();

            usort($allCountries, function ($a, $b) {
                return strcmp($a['name']['common'], $b['name']['common']);
            });

            if ($request->has('search')) {
                $search = $request->input('search');
                $search = preg_replace('/[^a-zA-Z\s]/', '', $search);
                $search = strtolower($search);

                $allCountries = array_filter($allCountries, function ($country) use ($search) {
                    return strpos(strtolower($country['name']['common']), $search) !== false;
                });
            }

            $perPage = 12;
            $currentPage = $request->input('page', 1);
            $totalCountries = count($allCountries);
            $countries = array_slice($allCountries, ($currentPage - 1) * $perPage, $perPage);

            
            foreach ($countries as &$country) {
                $country['formatted_population'] = $this->formatPopulation($country['population']);
            }

            return view('countries', [
                'countries' => collect($countries),
                'total' => $totalCountries,
                'perPage' => $perPage,
                'currentPage' => $currentPage,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Não foi possível carregar os países.']);
        }
    }

    private function formatPopulation($population)
    {
        if ($population >= 1000000000) {
            return round($population / 1000000000) . ' Bilhões';
        } elseif ($population >= 1000000) {
            return round($population / 1000000) . ' Milhões';
        } elseif ($population >= 1000) {
            return round($population / 1000) . ' Mil';
        }
        return $population;
    }

    public function fetchAndSaveCountries()
    {
        try {

            $allCountries = $this->apiService->getCountries();

            foreach ($allCountries as $country) {

                $capital = $country['capital'][0] ?? null;

                \App\Models\Country::updateOrCreate(
                    ['name' => $country['name']['common']],
                    [
                        'capital' => $capital,
                        'population' => $country['population'],
                    ]
                );
            }

            return response()->json(['message' => 'Países salvos com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao salvar países: ' . $e->getMessage()], 500);
        }
    }
}