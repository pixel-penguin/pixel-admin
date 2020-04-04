<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\PageContentGallery');
    }
}
