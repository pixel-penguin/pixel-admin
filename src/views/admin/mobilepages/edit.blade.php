@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Edit Page</h4>
		</div>
	</div>
	<admin-mobile-page-edit :page_id="{!! htmlspecialchars($page->id, ENT_QUOTES, 'UTF-8') !!}"></admin-mobile-page-edit>
</div>
  @stop
  @section('scripts')


  @stop