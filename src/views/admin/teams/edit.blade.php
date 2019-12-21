@extends('pixel-admin::layouts.admin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Edit Team Member</h4>
		</div>
	</div>
	<admin-team-edit :team_id_link="{!! htmlspecialchars($teamId, ENT_QUOTES, 'UTF-8') !!}"></admin-team-edit>
</div>
  @stop
  @section('scripts')


  @stop