@extends('layouts.master')

@section('content')

<header class="picturue-header"></header>
<section class="picture">
	<ul class="list">
		@foreach($pictures as $picture)
		<li class="animated">
			<a href="{{ $picture->image_size('w-960') }}" data-gallery>
				<img src="{{ $picture->image_size('s-120') }}">
			</a>
		</li>
		@endforeach
	</ul>
</section>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <ol class="indicator"></ol>
</div>

@stop