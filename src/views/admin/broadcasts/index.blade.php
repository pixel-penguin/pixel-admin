@extends('layouts.clientadmin')
@section('content')
	
<div class="content container-fluid">
	<div class="row">
		<div class="col-xs-8">
			<h4 class="page-title">Broadcasts</h4>
		</div>
		<div class="col-xs-4 text-right m-b-30">
			<a class="btn btn-primary rounded pull-right" href="/broadcasts/create"><i class="fa fa-plus"></i> Add Broadcast</a>
		</div>
	</div>
	<div class="row">
		
		<style>
			.label-success, .label-danger{ float: right}
		</style>
		@foreach($place->broadcasts as $broadcast)
		<div class="col-sm-6 col-md-6 col-lg-4">
			<div class="blog grid-blog">
				<!--
				<div class="blog-image">
					<a href="blog-details.html"><img class="img-responsive" src="assets/img/blog/blog-01.jpg" alt=""></a>
				</div>
				-->
				<div class="blog-content">
					<h3 class="blog-title">
						<a href="#">
							{{ $broadcast->name }} @if($broadcast->active == 'Y')
							<span class="label label-success">Active</span>
							@else
							<span class="label label-danger">Inactive</span>
							@endif
						</a>
					</h3>
					<p>{{ $broadcast->detail }}</p>
					<!--<a href="blog-details.html" class="read-more"><i class="fa fa-long-arrow-right" aria-hidden="true"></i> Read More</a>-->
					<div class="blog-info clearfix">
						<div class="post-left">
							<ul>
								<li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i> <span>{{ $broadcast->created_at->format('H\hm - d M Y') }}</span></a></li>
								
								
								
							</ul>
						</div>
						<div class="post-right">
							<a href="/broadcasts/{{ $broadcast->id }}/edit">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a>
							{{ Form::open(array('url' => '/broadcasts/'.$broadcast->id, 'method' => 'DELETE', 'id' => 'deleteButton'.$broadcast->id)) }}
								<i style="cursor: pointer" onclick="deleteEntry({{ $broadcast->id }})" class="fa fa-remove" aria-hidden="true"></i>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
		</div>
		@endforeach
		
		
		
	</div>
</div>
@stop
@section('scripts')

<script type="text/javascript">
	
	
	function deleteEntry(buttonID)
	{
		//document.getElementById('deleteButton'+buttonID.submit());
		
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
			  
			  $('#deleteButton'+buttonID).submit();	
				swal(
				  'Deleted!',
				  'Your file has been deleted.',
				  'success'
				)
		  }
		})
		
		
	}
</script>

@stop