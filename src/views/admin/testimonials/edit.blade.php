@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Edit Testimonial</h4>
		</div>
	</div>
	<admin-testimonial-edit :testimonial_id_link="{!! htmlspecialchars($testimonialId, ENT_QUOTES, 'UTF-8') !!}"></admin-testimonial-edit>
</div>
  @stop
  @section('scripts')


  @stop