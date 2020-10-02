<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\ProductCategory;
use PixelPenguin\Admin\Models\ProductCategoryGallery;
use PixelPenguin\Admin\Models\ProductCategoryContent;
use PixelPenguin\Admin\Models\ProductCategoryContentGallery;

use Cloudder;

class ProductCategoriesController extends Controller
{
    private $orderNumber = 0;
   
    public function index()
    {
		
        return view('pixel-admin::admin.productcategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	
    public function orderProductCategories(Request $request)
    {
		$input = $request->all();
		
		$productCategories = $input['productCategories'];





        foreach($productCategories as $productCategory)
        {
            $this->updateFields($productCategory);         
        }
		
		$response = array();
		$response['success'] = true;
		
		return $response;
	}
	
	
	private function updateFields($array, $id = 0)
    {
		
		
        $productCategory = ProductCategory::findOrFail($array['id']);
		
		$productCategory->parent_id = $id;
		$productCategory->column_order = $this->orderNumber;
		
		$productCategory->save();
		
		$this->orderNumber++;
		
		if(isset($array['children']))
        {
            foreach($array['children'] as $child)
            {
                $this->updateFields($child, $array['id']);
            }
        }        
    } 
	
    public function store(Request $request)
    {
        $input = $request->all();
		
		//dd($input);
		
		$productCategory = new ProductCategory();
		$productCategory->user_id = Auth::user()->id;
		$productCategory->name = $input['name'];
		
		$linkName = str_slug($productCategory->name, '-') ;
		
		$productCategory->link_name = $linkName.'-'.$productCategory->id;
		$productCategory->save();		
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
		$productCategory = ProductCategory::whereId($id)->first();
		
        return view('pixel-admin::admin.productcategories.edit',['productCategory' => $productCategory]);
    }
	
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
		
		$productCategory = ProductCategory::whereId($id)->first();
		
		$productCategory->name = $input['name'];
		
		$productCategory->link_name = $input['link_name'];
		$productCategory->title = $input['title'];
		
		$productCategory->detail = $input['detail'];
			
		$linkName = str_slug($productCategory->name, '-');
		$productCategory->link_name = $linkName.'-'.$productCategory->id;
				
		if(strlen($input['title']) < 1){
			
			$productCategory->title = $productCategory->name;
		}
		
		$productCategory->save();		
		
		$response = array();
		$response['success'] = true;
		
		return $response;
		//$linkName = str_slug($productCategory->name, '-');
		//$productCategory->link_name = $linkName.'-'.$productCategory->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productCategory = ProductCategory::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$productCategory = ProductCategory::findOrFail($input['id']);
		
		if($productCategory['active'] == true)
		{
			$productCategory->active = false;
		}
		else
		{
			$productCategory->active = true;
		}
		
		$productCategory->save();

		$response = array();
		$response['success'] = true;
		
		return $response;
	}
	
	
	
	
}
