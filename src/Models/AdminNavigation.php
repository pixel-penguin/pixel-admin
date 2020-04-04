<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNavigation extends Model
{
    public function children()
	{
		return $this->hasMany('PixelPenguin\Admin\Models\AdminNavigation', 'parent_id', 'id')->with('children');
	}
}
