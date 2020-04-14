<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Product;
use PixelPenguin\Admin\Models\ProductGallery;
use PixelPenguin\Admin\Models\ProductPrice;
use PixelPenguin\Admin\Models\ProductPriceKeyword;

use Auth;

use Cloudder;

class ProductsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.products.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.products.edit', ['productId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.products.create');
	}
	
	public function destroy($id)
    {
        $page = Product::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		$redirect = false;
		
		if($id > 0){
			$product = Product::whereId($id)->first();
		}
		else{
			$product = new Product();
			$redirect = true;
		}
		
		//step 1
		$product->name = $input['name'];		
		$product->link_name =  str_slug($product->name, '-').'-'.$product->id;
		$product->brand = $input['brand'];
		$product->product_category_id = $input['product_category_id'];
		$product->detail_summary = $input['detail_summary'];
		
		//step 2
		
		$product->colors()->detach();
		
		if(isset($input['colors'])){
			
			sort($input['colors']);
			
			foreach($input['colors'] as $color){

				$product->colors()->attach($color['id']);

			}
		}
		
		
		$product->attributes()->detach();
		
		if(isset($input['attributes'])){
			
			sort($input['attributes']);
			
			foreach($input['attributes'] as $attribute){
				//dd($attribute);
				$cleanTags = array();

				if(isset($attribute['value'])){

					foreach($attribute['value'] as $attributeValue){
						//dd($attributeValue);
						$cleanTags[] = $attributeValue['value'];
					}

					if(isset($cleanTags)){
						$product->attributes()->attach($attribute['id'], ['value' => implode(',', $cleanTags)]);		
					}

				}


			}
		}
		//step 3
		$product->detail = $input['detail'];
		
		
		//step 6
		$product->active = $input['active'];		
		$product->featured =  $input['featured'];
		
		
			
		
		//other step
		$product->user_id = Auth::user()->id;
		
		$cleanTags = array();
		
		foreach($input['tags'] as $tag){
			$cleanTags[] = $tag['value'];
		}
		
		$product->tags = implode(',', $cleanTags);
		
		
		$product->save();
		
		
		$this->createPricesForProducts($product->id);
		/*
		$product->categories()->detach();
		foreach($input['product_category'] as $category){
			$product->categories()->attach($category['id']);
		}
		*/
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $product;
		$response['redirect'] = $redirect;
		
		return $response;
	}
	
	private function createPricesForProducts($productId){
		
		$product = Product::whereId($productId)->with('colors')->with('attributes')->first();
		
		$product->prices()->update(['active' => false]);
		
		$combinations = array();
		
		$colors = array();
		
		foreach($product->prices()->get() as $price){
			$price->active = false;
			$price->save();
		}
		
		//dd($product->colors()->get()->toArray());
		foreach($product->colors()->get() as $color){
			$colors[] = $color->name;
		}
		
		if(isset($colors[0])){
			$combinations[] = $colors;	
		}
		
		
		//dd($combinations);
		
		if($product->attributes != false){
			foreach($product->attributes()->withPivot('value')->get() as $attribute){
				
				//dd($attribute);
				$combinations[] = explode(',', $attribute->pivot->value);
				
			}	
		}
		
		//Generates the combinations of customer choice options
		
		$variations = $this->combos($combinations);
		
		if(isset($variations[0])){
			foreach($variations as $variation){
			
				$name = implode(' ', $variation).' '.$product->name;
				$code = str_slug($name.'-'.$productId);

				$productPrice = ProductPrice::where('code', $code)->first();

				if($productPrice == false){
					$productPrice = new ProductPrice();	
				}

				$productPrice->user_id = Auth::user()->id;
				$productPrice->product_id = $productId;
				$productPrice->name = $name;
				$productPrice->code = $code;
				$productPrice->active = true;

				$productPrice->save();
				
				
				
				ProductPriceKeyword::where('product_price_id', $productPrice->id)->delete();
				
				foreach($variation as $entry){
					
					$productPriceKeyword = new ProductPriceKeyword();
					$productPriceKeyword->product_price_id = $productPrice->id;
					$productPriceKeyword->name = $entry;
					$productPriceKeyword->save();
				}


			}
		}
		else{
			$name = $product->name;
			$code = str_slug($name.'-'.$productId);	
			
			$productPrice = ProductPrice::where('code', $code)->first();

			if($productPrice == false){
				$productPrice = new ProductPrice();	
			}

			$productPrice->user_id = Auth::user()->id;
			$productPrice->product_id = $productId;
			$productPrice->name = $name;
			$productPrice->code = $code;
			$productPrice->active = true;

			$productPrice->save();
		}
		
		
		//dd($variations);
		
		
		
		
	}
	
	private function combos($data, &$all = array(), $group = array(), $val = null, $i = 0) {
		if (isset($val)) {
			array_push($group, $val);
		}
		if ($i >= count($data)) {
			array_push($all, $group);
		} else {
			foreach ($data[$i] as $v) {
				$this->combos($data, $all, $group, $v, $i + 1);
			}
		}
		return $all;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Product::findOrFail($input['id']);
		
		if($page['active'] == true)
		{
			$page->active = false;
		}
		else
		{
			$page->active = true;
		}
		
		$page->save();

		$response = array();
		$response['success'] = true;
		
		return $response;
	}
	
	public function updatePrices(Request $request){
		$input = $request->all();
		
		foreach($input['prices'] as $price){
			$priceEntry = ProductPrice::whereId($price['id'])->first();
			
			$priceEntry->price = $price['price'];
			$priceEntry->discounted_price = $price['discounted_price'];
			$priceEntry->is_discount = $price['is_discount'];
			
			$priceEntry->save();
		}
		
		return [
			'success' => true			
		];
	}
	
	public function updateImage(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_product_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Product::whereId($input['product_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function uploadGallery(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	Cloudder::upload($image_name->getRealPath(),  env('CLOUDINARY_BASE_FOLDER_PATH').'app_product_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new ProductGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->product_id = $input['product_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function deleteGallery($id){
		//dd($id);
		
		$gallery = ProductGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function galleryOrder(Request $request, $pageId){
		$input = $request->all();
		
		//dd($input);
		$count = 0;
		
		foreach($input['productGallery'] as $gallery){
			$galleryEntry = ProductGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
}
