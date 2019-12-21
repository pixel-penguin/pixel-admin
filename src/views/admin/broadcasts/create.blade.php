@extends('layouts.clientadmin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Add Broadcast</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			{{ Form::open(array('route' => 'broadcasts.store', 'class' => 'usersForm')) }}
			
				<input name="place_id" type="hidden" value="{{ $place->id }}" />
			
				<div class="form-group">
					<label>Broadcast Name</label>
					<input class="form-control" type="text" name="name" required>
				</div>
			
				
			
				<div class="m-t-20 text-center">
					<button class="btn btn-primary btn-lg">Add Broadcast</button>
				</div>
			 {{ Form::close() }}
		</div>
	</div>
</div>
  @stop
  @section('scripts')
  @stop