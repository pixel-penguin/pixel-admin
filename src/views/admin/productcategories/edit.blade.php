@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Edit Product Listing</h4>
		</div>
	</div>
	<admin-product-categories-edit :product_category_id="{!! htmlspecialchars($productCategory->id, ENT_QUOTES, 'UTF-8') !!}"></admin-product-categories-edit>
</div>
  @stop
  @section('scripts')


  @stop