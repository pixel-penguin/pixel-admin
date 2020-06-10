<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Sale;

use Auth;

use Cloudder;

class OrdersController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.orders.index');
	}	
	
	
	public function getOrders($type){
		
		
		if($type == 'outstanding'){
			
			$orders = Sale::where('done', false)->get();
		}
		
		
		return [
			'success' => true,
			'data' => $orders
		];
		
	}
	
	public function updateDelivery(Request $request){
		
		$input = $request->all();
		
		$order = Sale::whereId($input['id'])->first();
		
		$order->delivery_status = $input['value'];
		
		$order->save();
		
		return [
			'success' => true			
		];
	}
	
	public function updatePayments(Request $request){
		
		$input = $request->all();
		
		$order = Sale::whereId($input['id'])->first();
		
		$order->payment_status = $input['value'];
		
		$order->save();
		
		return [
			'success' => true			
		];
	}
	
	public function changeOrderStatus(Request $request){
		
		$input = $request->all();
		
		$order = Sale::whereId($input['id'])->first();
		
		if($order->done == true){
			$order->done = false;
		}
		else{
			$order->done = true;
		}
		
		$order->save();
		
		return [
			'success' => true
		];
		
	}
	
}
