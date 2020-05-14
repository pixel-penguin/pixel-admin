<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPackage extends Model
{
   public function includes()
   {
        return $this->belongsToMany('PixelPenguin\Admin\Models\TravelPackageInclude');
   }
	
   public function excludes()
    {
        return $this->belongsToMany('PixelPenguin\Admin\Models\TravelPackageExclude');
    }
	
	public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\TravelPackageGallery');
    }
	
	public function travelDates()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\TravelPackageTravelDate');
    }
	
	public function itineraries()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\TravelPackageItinerary')->orderBy('column_order');
    }
	
	
}
