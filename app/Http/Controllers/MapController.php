<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
class MapController extends Controller
{
    public function index()
    {
        $countries = Country::latest()->get();

        return view('censusmap', compact('countries'));
    }

    public function getCityStores(Request $request)
    {
        $cityId = $request->city;

        $stores = DB::table('stores')->select('StoreId', 'StoreName', 'stores.Latitude', 'stores.Longitude', 'Sales', 'CityName')->join('cities', 'stores.CityId', '=', 'cities.CityId')->where('cities.CityName', '=', $cityId)->get();

        if ($stores) {
            $iIndex = 0;
            $dataStores = [];
            foreach ($stores as $key => $value) {
                $dataStores[$iIndex] = [$value->StoreId, $value->StoreName, $value->Latitude, $value->Longitude, $value->Sales, $value->CityName];

                $iIndex++;
            }

            return response()->json(['success' => true, 'message' => 'Stores Fetched Successfully!', 'data' => \json_encode($dataStores)], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No Stores Found'], 200);
        }
    }

    public function getCityDetails(Request $request)
    {
        $sumPopulation = 0;
        $sumArea = 0;
        $sumGender = ['Male' => 0, 'Female' => 0];
        $sumDensity = 0;
        $sumUrbanRural = ['Urban' => 0, 'Rural' => 0];
        $sumLiteracy = ['Yes' => 0, 'No' => 0];

        $sumAgeDistribution = [
            '0-9 years' => 0,
            '10-19 years' => 0,
            '20-29 years' => 0,
            '30-39 years' => 0,
            '40-49 years' => 0,
            '50-59 years' => 0,
            '60-69 years' => 0,
            '70+ years' => 0,
        ];

        $cities = DB::table('cities')
            ->select('CityId', 'CityName', 'Population', 'Area', 'Density', 'Gender', 'UrbanRural', 'AgeDistribution', 'Literacy')
            ->where('CityName', '=', $request->City)
            ->get();

        foreach ($cities as $item) {
            // $sumPopulation += intval(str_replace(',', '', $item->Population));
            // $sumArea += floatval(str_replace(' km²', '', $item->Area));
            // $sumDensity += intval(str_replace(',', '', $item->Density));

            $sumPopulation += intval(str_replace(',', '', rand(10000, 500000)));
            $sumArea += floatval(str_replace(' km²', '', rand(10000, 500000)));
            $sumDensity += intval(str_replace(',', '', rand(10000, 500000)));

            $item->UrbanRural = json_decode($item->UrbanRural);

            // $sumUrbanRural['Urban'] += intval(str_replace(',', '', $item->UrbanRural->Urban));
            // $sumUrbanRural['Rural'] += intval(str_replace(',', '', $item->UrbanRural->Rural));

            $sumUrbanRural['Urban'] += intval(str_replace(',', '', rand(10000, 500000)));
            $sumUrbanRural['Rural'] += intval(str_replace(',', '', rand(10000, 500000)));

            $item->Literacy = json_decode($item->Literacy);
            $sumLiteracy['Yes'] += intval(str_replace(',', '', $item->Literacy->Yes));
            $sumLiteracy['No'] += intval(str_replace(',', '', $item->Literacy->No));

            $sumLiteracy['Yes'] += intval(str_replace(',', '', rand(10000, 500000)));
            $sumLiteracy['No'] += intval(str_replace(',', '', rand(10000, 500000)));

            $item->Gender = json_decode($item->Gender);

            $sumGender['Male'] += intval(str_replace(',', '', $item->Gender->Male));

            // $sumGender['Female'] += intval(str_replace(',', '', $item->Gender->Female));
            // $sumGender['Transgender'] += intval(str_replace(',', '', $item->Gender->Transgender));

            $sumGender['Female'] += intval(str_replace(',', '', rand(10000, 500000)));
            // $sumGender['Transgender'] += intval(str_replace(',', '', rand(10000, 500000)));

            $item->AgeDistribution = json_decode($item->AgeDistribution);

            foreach ($item->AgeDistribution as $ageGroup => $count) {
                $sumAgeDistribution[$ageGroup] += rand(1000, 50000);
            }
        }

        $sumGenderChart = [['label' => 'Male', 'value' => $sumGender['Male']], ['label' => 'Female', 'value' => $sumGender['Female']]];
        $sumUrbanChart = [['label' => 'Urban', 'value' => $sumUrbanRural['Urban']], ['label' => 'Rural', 'value' => $sumUrbanRural['Rural']]];
        $sumLiteracyChart = [['label' => 'Yes', 'value' => $sumLiteracy['Yes']], ['label' => 'No', 'value' => $sumLiteracy['No']]];

        $sumAgeChart = [];
        foreach ($sumAgeDistribution as $label => $value) {
            $sumAgeChart[] = ['label' => $label, 'value' => $value];
        }
        if ($cities) {
            return response()->json(['success' => true, 'TotalStores' => 0, 'sales' => 0 ?? 0, 'literacyChart' => $sumLiteracyChart, 'ageChart' => $sumAgeChart, 'urbanChart' => $sumUrbanChart, 'genderChart' => $sumGenderChart, 'Literacy' => $sumLiteracy, 'AgeDistribution' => $sumAgeDistribution, 'UrbanRural' => $sumUrbanRural, 'population' => $sumPopulation, 'area' => $sumArea, 'density' => $sumDensity, 'gender' => $sumGender, 'message' => 'City Details fetched successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'No City Details Found!']);
        }
    }
}
