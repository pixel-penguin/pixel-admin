@extends('layouts.clientadmin')
@section('content')
<div class="content container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Update Chat Channel</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			{{ Form::open(array('route' => ['broadcasts.update', $broadcast->id], 'class' => 'usersForm', 'method' => 'PATCH')) }}
				
				<div class="form-group">
					<label>Chat Channel Name</label>
					<input value="{{ $broadcast->name }}" class="form-control" type="text" name="name" required>
				</div>
			
				<div class="form-group">
					<label class="display-block">Chat Channels in Broadcast</label>
					{{ Form::select('chat_channel_ids[]', App\PlaceChatChannel::where('place_id', $placeId)->pluck('name','id')->toArray(), $broadcast->chatChannels->pluck('id')->toArray(), array('class' => 'form-control', 'multiple' => 'multiple', 'id' => 'users')) }}
					
				</div>
				
				<table class="table table-bordered">
					<thead>
					  <tr>
						<th>Messages</th>
						<th>Action</th>
					  </tr>
					</thead>
					<tbody>
						
					  @foreach($broadcast->messages as $message)
					  <tr>
						<td>{{ $message->message }}</td>
						<td>
							
							<div data-id="{{ $message->id }}" class="btn btn-danger deleteMessage" type="submit">Delete</div>
							
						</td>
					  </tr>
					  @endforeach
					</tbody>
				  </table>
				<div class="m-t-20 text-center">
					<a href="/broadcasts/addmessage/{{ $broadcast->id }}" class="btn btn-default btn-lg">Add message</a>
					<button class="btn btn-primary btn-lg">Update Channel</button>
					<a href="/broadcasts" class="btn btn-default btn-lg">Cancel</a>
				</div>
			 {{ Form::close() }}
		</div>
	</div>
</div>
  @stop
  @section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			
			$('.deleteMessage').click(function(){
				
				var messageId = $(this).attr('data-id');
				
				var currentMessage = $(this);
				
				swal({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				  if (result.value) {

					  $.ajax({
					  type: "POST",
					  url: '/broadcasts/messagedestroy/'+messageId,
					  //data: data,
					  success: function(){
						  
						  currentMessage.parent().parent().fadeOut();
						  
						swal(
						  'Deleted!',
						  'Your file has been deleted.',
						  'success'
						)
					  }
					});
						
				  }
				})
			});
				
			
			
			
			$('#users').select2({
				tags:true,
			});
			
			$('#connected_users').select2({
				tags:true,
			});
			
			
			$('input[type=radio][name=group_chat]').change(function() {
				displayGroupChatSettings();
			});
			
			displayGroupChatSettings();
		});
		
		function displayGroupChatSettings()
		{
			if($("input[type='radio'][name='group_chat']:checked").val() == 'Y')
			{
				$('.isGroupChat').show();
			}
			else
			{				
				$('.isGroupChat').hide();
			}
		}
		
	</script>
  @stop