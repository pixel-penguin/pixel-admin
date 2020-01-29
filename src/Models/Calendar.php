<?php

namespace PixelAdmin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    public function categories()
    {
        return $this->belongsToMany('PixelAdmin\Admin\Models\CalendarCategory');
    }
}
