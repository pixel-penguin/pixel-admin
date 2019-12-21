@extends('pixel-admin::layouts.admin')
@section('content')
		
	<div class="content container-fluid">
    	<div class="row">
			<admin-dashboard-analytics></admin-dashboard-analytics>
		</div>
	</div>



		
  @stop
  @section('scripts')
  @stop

@section('style')
<style>
	.filterStatsProperty input{ padding: 10px 20px;  border: 1px solid #CCC; border-radius:5px; }
</style>
@stop