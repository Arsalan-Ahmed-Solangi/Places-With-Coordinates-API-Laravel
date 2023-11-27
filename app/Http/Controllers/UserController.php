<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
class UserController extends Controller
{
    public function index(){
        try {
            $countires = Country::latest()->get() ?? array();
        } catch (\Throwable $th) {
           $countires = array();
        }
        return view('dashboard',compact('countires'));
    }

    public function getCities(Request $request){
        $cities = City::latest()->where("CountryId",'=',$request->id)->get();
        if($cities){
            return response()->json(array('status'=>200,'cities'=>$cities));
        }else{
            return response()->json(array('status'=>404,'message'=>'No Cities Found'));
        }
    }
}
