<?php

namespace PixelPenguin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use PixelPenguin\Admin\Models\SaleShippingMethod;
use PixelPenguin\Admin\Models\SaleShippingMethodCity;
use PixelPenguin\Admin\Models\SaleShippingMethodCityExclude;
use PixelPenguin\Admin\Models\SaleShippingMethodCountry;
use PixelPenguin\Admin\Models\SaleShippingMethodCountryExclude;

use PixelPenguin\Admin\Models\Sale;
use PixelPenguin\Admin\Models\SaleShippingDetail;
use PixelPenguin\Admin\Models\SaleProduct;
use App\User;

use Auth;

class CartJsonController extends Controller
{
    public function getDeliverOptionsWithValue($cityId, $countryId, $shipopingMethodId){
		
		$price = 0;
		$deliverable = false;
		
		$shippingMethods = SaleShippingMethod::All();
		
		$shippinMethod = SaleShippingMethod::whereId($shipopingMethodId)->first();
		
		//dd($shippinMethod);
		
		if($shippinMethod != false && $shippinMethod->price_by_country == true){
			$price = $this->getPriceByCity($cityId);
		
			if($price == 'NOPrice'){
				$price = $this->getPriceByCountry($countryId);
			}

			//echo $price;
			if($price != 'NA'){
				$deliverable = true;
			}

			if($price == 'NOPrice'){
				$price = 0;
			}	
		}
		else{
			$deliverable = true;
			$price = 0;			
		}
		
		
		return [
			'success' => true,
			'shippingMethods' => $shippingMethods,
			'shippingMethod' => $shippinMethod,
			'price' => $price,
			'deliver' => $deliverable
		];
		
	}
	private function getPriceByCity($cityId){
		$cityMethod = SaleShippingMethodCityExclude::where('city_id', $cityId)->first();
		
		if($cityMethod != false){
			return 'NA'; //Stop Script
		}
		
		$cityMethod = SaleShippingMethodCity::where('city_id', $cityId)->first();
		
		if($cityMethod == false){
			return 'NOPrice'; //Stop Script
		}
		else{
			return $cityMethod->price;
		}
	}
	private function getPriceByCountry($countryId){
		
		$countryMethod = SaleShippingMethodCountryExclude::where('country_id', $countryId)->first();
		if($countryMethod != false){
			return 'NA'; //Stop Script
		}
		
		$countryMethod = SaleShippingMethodCountry::where('country_id', $countryId)->first();
		
		if($countryMethod == false){
			return 'NOPrice'; //Stop Script
		}
		else{
			return $countryMethod->price;
		}
	}
	
	public function createNewOrder(Request $request){
		 
		$input = $request->all();
		
		//dd($input);
		
		$price = $this->getDeliverOptionsWithValue($input['user']['city_id'], $input['user']['country_id'], $input['sale_shipping_method_id']);
		
		$price = $price['price'];
		
		if($price == 'NA'){
			return [
				'success' => false,
				'message' => "note a valid shipping method"
				
			];
		}
		
		$sale = new Sale();
		$sale->user_id = $input['user']['id'];
		$sale->payment_status = 'Pending';
		$sale->delivery_status = 'Pending';
		$sale->reference_code = $this->generateUniqueString();
		$sale->sale_shipping_method_id = $input['sale_shipping_method_id'];
		$sale->sale_shipping_price = $price;
		
		$sale->save();
		
		$saleShipingDetail = new SaleShippingDetail();
		
		$saleShipingDetail->sale_id = $sale->id;
		$saleShipingDetail->contact_person = $input['user']['name'];
		$saleShipingDetail->postal_address = $input['user']['postal_address'];
		$saleShipingDetail->country_id = $input['user']['country_id'];
		$saleShipingDetail->city_id = $input['user']['city_id'];
		$saleShipingDetail->postal_code = $input['user']['postal_code'];
		$saleShipingDetail->contact_number = $input['user']['contact_number'];
		$saleShipingDetail->street_address_1 = $input['user']['street_address_1'];
		$saleShipingDetail->street_address_2 = $input['user']['street_address_2'];
		$saleShipingDetail->postal_address = 'N/A';
		$saleShipingDetail->save();
		
		$user = User::whereId(Auth::user()->id)->first();
		$user->postal_address = $input['user']['postal_address'];
		$user->country_id = $input['user']['country_id'];
		$user->city_id = $input['user']['city_id'];
		$user->postal_code = $input['user']['postal_code'];
		$user->contact_number = $input['user']['contact_number'];
		$user->street_address_1 = $input['user']['street_address_1'];
		$user->street_address_2 = $input['user']['street_address_2'];
		$user->save();
		
		
		
		if ($request->session()->exists('cart')) {
			$cart = $request->session()->get('cart');
		}
		else{
			$cart = collect();
		}
		
		foreach($cart as $cartEntry){
			$saleProduct = new SaleProduct();
			$saleProduct->sale_id = $sale->id;
			$saleProduct->product_price_id = $cartEntry['id'];
			$saleProduct->quantity = $cartEntry['quantity'];
			$saleProduct->price = $cartEntry['price'];
			$saleProduct->save();
		}
		
		 $request->session()->forget('cart');
		
		return [
			'success' => true,
			'data' => $sale
		];
		
	}
	
	private function generateUniqueString(){
		
		$randomString = Str::random(13);
		if (Sale::where('reference_code', $randomString)->exists()) {
			return $this->generateUniqueString();
		}
		else{
			return $randomString;
		}
	}
}
