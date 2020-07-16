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
			{{ Form::open(array('route' => 'pagebuilder.store', 'class' => 'usersForm')) }}
				<div class="form-group">
					<label>Page Center Name</label>
					<input class="form-control" type="text" name="name" required>
				</div>
				
				<input type="hidden" name="place_id" value="{{ $place->id }}" />
				
				<div class="form-group">
					<label>Icon</label>

					<div id="icon" style="padding: 20px; border: 1px solid #EBEBEB; background: #FFF; margin-bottom: 5px; display: table"></div>
					<select id="select" name="menu_icon" class="fa-select"></select>
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
					<a href="/pagebuilder" class="btn btn-default btn-lg">Canel</a>
				</div>
			 {{ Form::close() }}
		</div>
	</div>
</div>
  @stop
  @section('scripts')

	<script type="text/javascript">
		
		function isset ( strVariableName ) { 
			
			//console.log(strVariableName);
			
			if(strVariableName == undefined)
			{
				return false;
			}
			
			try { 
				eval( strVariableName );
			} catch( err ) { 
				if ( err instanceof ReferenceError ) 
				   return false;
			}

			return true;

		 } 
		
		$(document).ready(function(){
			$.get('/json/icons.yml', function(data) {

				var parsedYaml = jsyaml.load(data);
				var options = new Array();
				$.each(parsedYaml.icons, function(index, icon){
					var keywords = '';
					//console.log(isset(icon.filter));
					if (isset(icon.filter) == true) {
						icon.filter.forEach(function(filterName) {
							keywords += filterName+' ';						  
							//console.log(filterName);
						});
					};
					
					//console.log('Name: '+ icon.name);
					//console.log('Keywords: '+ keywords);
					options.push({
						id: icon.id,
						text: '<i data-id="'+keywords+'" class="fa fa-fw fa-' + icon.id + '"></i> ' + icon.id
					});
			  });

				$('#select').select2({
					data: options,
					escapeMarkup: function(markup) {
						return markup;
					}
				});
				$("#icon").html('<i class="fa fa-2x fa-' + $('#select').val() + '"></i>');
			});

			// Detect any change of option
			$("#select").change(function(){
			  var icono = $(this).val();
				$("#icon").html('<i class="fa fa-2x fa-' + icono + '"></i>');
			});
		});

	</script>


  @stop