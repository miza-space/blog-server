@foreach ($data as $blog)
<article class="time-box animated">
    <div class="content">
        @if (isset($blog['media']['pic']))
            <?php $pics = $blog['media']['pic']; ?>
            @if (count($pics) > 1)
            <div class="pictures gallery" data-gindex="0">
                <div class="blueimp-gallery blueimp-gallery-carousel blueimp-gallery-controls">
                    <div class="slides"></div>
                    <ol class="indicator"></ol>
                </div>
                <div class="gallery-holder" style="background-image: url({{ $pics[0]['src'] }}); background-size:cover;"></div>
                <a class="gallery-tool gallery-prev">‹</a>
                <a class="gallery-tool gallery-next">›</a>
                <a class="pic-fullscreen">fullscreen</a>
                <?php
                $gallery_pics = array();
                foreach ($pics as $pic) {
                    $gallery_pics[] =  $pic['path'] . $pic['src'];
                }
                ?>
                <textarea class="hide gallery-data">{{ json_encode($gallery_pics) }}</textarea>
            </div>
            @else
            <div class="pictures">
                <img src="{{ $pics[0]['path'] }}{{ $pics[0]['src'] }}">
                <a class="pic-fullscreen">fullscreen</a>
            </div>
            @endif
        @endif
        @if ($blog['title'])
        <div class="desc">
            @if ($blog['content'])
            <h3>{{ $blog['title'] }}</h3>
            <p>{{ $blog['content'] }}</p>
            @else
            <h3 class="small">{{ $blog['title'] }}</h3>
            @endif
        </div>
        @endif
        <div class="meta">
            <a class="meta-tool share" title="share to weibo"></a>
            <span class="date">{{ $blog['created_at'] }}</span>
            @if ($blog['location'])
            <span class="point">•</span>
            <a class="location" data-lon="{{ $blog['location']['lon'] }}" data-lat="{{ $blog['location']['lat'] }}">{{ $blog['location']['label'] }}</a>
            @endif
        </div>
    </div>
</article>
@endforeach