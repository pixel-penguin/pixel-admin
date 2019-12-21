@extends('layouts.clientadmin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Add Broadcast Message</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			{{ Form::open(array('route' => 'broadcasts.messagestore', 'class' => 'usersForm')) }}
			
				<input name="chat_single_channel_broadcast_id" type="hidden" value="{{ $broadcast->id }}" />
			
				<div class="form-group">
					<label>Message</label>
					<input class="form-control" type="text" name="message" required>
				</div>
			
				<div class="m-t-20 text-center">
					<button class="btn btn-primary btn-lg">Add Message</button>
					<a href="/broadcasts/{{ $broadcast->id }}/edit" class="btn btn-default btn-lg">Cancel</a>
				</div>
			 {{ Form::close() }}
		</div>
	</div>
</div>
  @stop
  @section('scripts')
  @stop