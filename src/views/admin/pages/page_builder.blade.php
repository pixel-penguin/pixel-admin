@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Content Editor</h4>
		</div>
	</div>
	<admin-page-builder :page_id="{!! htmlspecialchars($page->id, ENT_QUOTES, 'UTF-8') !!}"></admin-page-builder>
</div>
@stop
@section('scripts')


@stop
@section('style')
<style>
	.pageChoice{ text-align: center; cursor: pointer}
	
	.pageChoice img{max-width: 90%}
	
	.pageChoice>div{background:#FFF; padding: 10px 0; margin:10px; height: 180px; border:#ededed 1px solid; border-radius:10px}
	
	.pageChoice p{font-size: 17px; margin-top: 10px}
</style>
@stop