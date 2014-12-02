@extends('layouts.master')

@section('content')

<header class="header">
    <div class="header-wrap">
        <div class="my-cover"><img src="/images/cover.png" /></div>
        <h1>ZHANGGE</h1>
        <p>自娱自乐地儿</p>
    </div>
</header>

<section class="timeline">
    @include('layouts.blog', array('data' => $data))
</section>

<div class="more-blogs">
    <div class="more <?php echo (count($data) == 0) ? 'error no-data' : ''; ?>" data-skip="{{ $skip }}" data-take="{{ $take }}" data-page="{{ $page }}">
        <?php echo (count($data) == 0) ? '还木有博客呢' : ''; ?>
    </div>
</div>
@stop