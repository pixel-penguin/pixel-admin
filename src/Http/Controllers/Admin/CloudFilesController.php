<?php

namespace PixelAdmin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelAdmin\Admin\Models\Team;

use Auth;

use Cloudder;

class TeamsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.teams.index');
	}
	
	
}
