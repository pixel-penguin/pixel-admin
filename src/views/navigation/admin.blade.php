
<li class="@if(strlen($sub) > 4) submenu @endif">
	
	
	<a @if(strlen($sub) > 4) href="#" @else  href="/{{ $navigation->url }}" @endif>
		<i class="fa {{ $navigation->icon_name }}" aria-hidden="true"></i>
		<span> {{ $navigation->name}}</span>
		
		@if(strlen($sub) > 4)
		<span class="menu-arrow"></span>
		@endif
	</a>
	
	@if(strlen($sub) > 4)
	<ul  class="list-unstyled" style="display: none;">
		{!! $sub !!} 
	</ul>
	@endif
</li>