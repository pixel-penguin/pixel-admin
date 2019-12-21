@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Create Testimonial</h4>
		</div>
	</div>
	<admin-testimonial-edit :testimonial_id_link="{!! htmlspecialchars(0, ENT_QUOTES, 'UTF-8') !!}"></admin-testimonial-edit>
</div>
  @stop
  @section('scripts')


  @stop