<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Hash;


use PixelPenguin\Admin\Models\WebsiteDetail;
use PixelPenguin\Admin\Models\HelperClasses\AdminFeatures;
use Illuminate\Support\Facades\Route;

//models
use PixelPenguin\Admin\Models\UserMenu;
use PixelPenguin\Admin\Models\User;
use PixelPenguin\Admin\Models\UserRole;

class UsersController extends Controller
{
    public function index()
	{
		//For Every Page
		$AdminFeatures = new AdminFeatures;
		$this->WebsiteDetail = WebsiteDetail::whereId(1)->first();
		$this->elapsedTime = $AdminFeatures->getElapsedTime();
		$routeName = Route::getFacadeRoot()->current()->uri();
		$routeName1 = AdminFeatures::stripRouteName($routeName,0); 
		$routeName2 = AdminFeatures::stripRouteName($routeName,1); 
		$routeName = "$routeName1/$routeName2";
		$navigationInfo = UserMenu::where('url', '=', $routeName)->firstOrFail();     
		//End for Every page
		
				
		$users = User::All();
		
		//echo '<pre>';
		//print_r($users);
		//echo '</pre>';
		//die();
		
		
		$UsersRole = UserRole::getAllLists();
		
		//echo '<pre>';
		//print_r($UsersRole);
		//echo '</pre>';
		//die();
		
		return view('admin.users.index', ['UsersRole' => $UsersRole, 'users' => $users, 'WebsiteDetail' => $this->WebsiteDetail, 'AdminUser' => Auth::user(), 'elapsedTime' => $this->elapsedTime,'navigationInfo' => $navigationInfo]);
	}
	
	public function store(Request $request)
	{
		$adminFeatures = new AdminFeatures;
		
		$input = $request->all();		
		
		$input['user_id'] = Auth::user()->id;

		$input['password'] = Hash::make($input['password']);
		
		$user = User::create($input);

		
		$adminFeatures->saveRecordInDatabase('User Added', "$user->name with id $user->id was added to database");

		return redirect()->route('admin.users.index');
	}
	
	public function edit($id)
	{
		//For Every Page
		$AdminFeatures = new AdminFeatures;
		$this->WebsiteDetail = WebsiteDetail::whereId(1)->first();
		$this->elapsedTime = $AdminFeatures->getElapsedTime();
		$routeName = Route::getFacadeRoot()->current()->uri();
		$routeName1 = AdminFeatures::stripRouteName($routeName,0); 
		$routeName2 = AdminFeatures::stripRouteName($routeName,1); 
		$routeName = "$routeName1/$routeName2";
		$navigationInfo = UserMenu::where('url', '=', $routeName)->firstOrFail();     
		//End for Every page
		
		$user = User::whereId($id)->first();
		
		$UsersRole = UserRole::getAllLists();
		
		
		return view('admin.users.edit', ['UsersRole' => $UsersRole, 'user' => $user, 'WebsiteDetail' => $this->WebsiteDetail, 'AdminUser' => Auth::user(), 'elapsedTime' => $this->elapsedTime,'navigationInfo' => $navigationInfo]);
	}
	
	public function update($id, Request $request)
	{
		$adminFeatures = new AdminFeatures;
		$input = $request->all();
		
		//echo '<pre>';
		//print_r($input);
		//echo '</pre>';
		//die();
		
		$user = User::findOrFail($id);

		if (strlen($input['password']) > 0)
		{
			$user->fill($input);
			$user->password = Hash::make($input['password']);
		}
		else
		{
			unset($input['password']);
			$user->fill($input);
		}

		//dd($user);


		$user->save();
		
		$adminFeatures->saveRecordInDatabase('User Edited', "$user->name with id $user->id was edited");

		return redirect()->route('admin.users.index');
	}
	
	public function destroy($id)
	{
		$adminFeatures = new AdminFeatures;
		$user = User::find($id);
		
		$adminFeatures->saveRecordInDatabase('User Removed', "$user->name with id $user->id was removed");
		
		$user->delete();
		
		return redirect()->route('admin.users.index');
	}
}
