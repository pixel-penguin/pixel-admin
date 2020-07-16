@extends('layouts.clientadmin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Add Page</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			{{ Form::open(array('route' => ['clientadmin.mediacenters.page.store', $mediaCenter->id], 'class' => 'usersForm')) }}
				<div class="form-group">
					<label>Page Name</label>
					<input class="form-control" type="text" name="name" required>
				</div>
			
				<div class="form-group">
					<label>Page Name</label>
					<select class="form-control" type="text" name="page_type_id">
						<option value="1">Content</option>
						<option value="2">Channel</option>
					</select>
				</div>
				
				
				<div class="form-group">
					<label>Page Content</label>
					<textarea cols="30" rows="6" class="form-control" name="detail" required></textarea>
				</div>
				
				<div class="form-group">
					<label class="display-block">Status</label>
					<label class="radio-inline">
						<input name="active" value="Y" checked="checked" type="radio"> Active
					</label>
					<label class="radio-inline">
						<input name="active" value="N" type="radio"> Inactive
					</label>
				</div>
				<div class="m-t-20 text-center">
					<button class="btn btn-primary btn-lg">Add Page</button>
				</div>
			 {{ Form::close() }}
		</div>
	</div>
</div>
  @stop
  @section('scripts')
  @stop