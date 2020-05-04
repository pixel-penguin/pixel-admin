@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Edit Project</h4>
		</div>
	</div>
	<admin-travel-package-edit :travel_package_id_link="{!! htmlspecialchars($travelPackageId, ENT_QUOTES, 'UTF-8') !!}"></admin-travel-package-edit>
</div>
  @stop
  @section('scripts')


  @stop

@section('style')
<style>

.filterStatsProperty input{ padding: 10px 20px;  border: 1px solid #CCC; border-radius:5px; width: 100% }

</style>
@endsection