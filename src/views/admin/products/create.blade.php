@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-12">
             
			
			<h4 class="page-title"><a href="/admin/products" class="btn btn-default btn-sm"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a> Create Product</h4>
		</div>
	</div>
	<admin-products-edit :product_id_link="{!! htmlspecialchars(0, ENT_QUOTES, 'UTF-8') !!}"></admin-products-edit>
</div>
  @stop
  @section('scripts')


  @stop