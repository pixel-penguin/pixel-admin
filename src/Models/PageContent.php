<?php

namespace PixelAdmin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    public function gallery()
    {
        return $this->hasMany('PixelAdmin\Admin\Models\PageContentGallery');
    }
}
