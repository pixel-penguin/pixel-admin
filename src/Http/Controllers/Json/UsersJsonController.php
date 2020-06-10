<?php

namespace PixelPenguin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

use Auth;

class UsersJsonController extends Controller
{
    public function getCurrentUserDetail(){
		
		$userId = Auth::user()->id;
		
		$user = User::whereId($userId)->first();
		
		return [
			'success' => true,
			'obj' => $user
		];
		
	}
}
