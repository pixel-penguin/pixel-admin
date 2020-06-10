<?php

namespace PixelPenguin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Country;
use PixelPenguin\Admin\Models\City;

class CountryJsonController extends Controller
{
    public function getAllCountries(){
		
		$countries = Country::orderBy('name')->get();
		
		return [
			'success' => true,
			'obj' => $countries
		];
		
	}
    public function getAllCitiesByCountry($countryId){
		
		$cities = City::where('country_id', $countryId)->orderBy('name')->get();
		
		return [
			'success' => true,
			'obj' => $cities
		];
		
	}
}
