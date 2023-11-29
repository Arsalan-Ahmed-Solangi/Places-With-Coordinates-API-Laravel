<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
use File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ByteOrderMark;

class UserController extends Controller
{
    public function index()
    {
        try {
            $countires = Country::latest()->get() ?? array();
        } catch (\Throwable $th) {
            $countires = array();
        }
        return view('dashboard', compact('countires'));
    }

    public function getCities(Request $request)
    {
        $cities = City::latest()->where("CountryId", '=', $request->id)->get();
        if ($cities) {
            return response()->json(array('status' => 200, 'cities' => $cities));
        } else {
            return response()->json(array('status' => 404, 'message' => 'No Cities Found'));
        }
    }

    public function downloadCSV(Request $request)
    {
        try {

            $stores = $request->input("csvData");

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ];

            if (!File::exists(public_path() . '/files')) {
                File::makeDirectory(public_path() . '/files');
            }

            $filename = public_path('files\stores.csv');

            $handle = fopen($filename, 'w');

            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));


            $headersBold = ["Store Name", "Address", "Latitude", "Longitude", "City"];

            fputcsv($handle, $headersBold);

            foreach ($stores as $store) {
                fputcsv($handle, [$store["storeName"], $store["storeAddress"], $store["storeLat"], $store["storeLng"], $store["storeCity"]]);
            }

            fclose($handle);

            return response()->json([
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "err" => $th->getMessage(),
                "status" => false
            ]);
        }
    }
}
