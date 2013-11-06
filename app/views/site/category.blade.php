@extends('site.main')

@section('content')
<div class="content">
    
    <div class="PeachSolidBlock650 ml0">
        <a class="news_rss" href="/rss/"><img src="/images/site/main/news_rss.png" alt=""></a>
        <div class="PeachSolidBlock650Cat_top"></div>
        <div class="PeachSolidBlock650_header">{{ $categoryName }}</div>
        <div class="PeachSolidBlock650_cont">
            
            <div class="catBlocks">
            
            @foreach ($articles as $key => $article)
                <div class="news @if(($key%2) == 0 ) left @else right @endif"> 
                    <img src="{{ $article['preview'] }}" width="100" height="76" alt="">
                    <h4><a href="{{ $url }}{{ str_replace('-', '/', $article['created_at']) }}/{{ $article['alias'] }}/">
                    {{ $article['article_name'] }}</a></h4>
                    {{ strip_tags(mb_substr($article['content'], 0, 90)) }} ...
                    <div class="info">
                        <span class="date">{{ $article['created_at'] }}</span> 
                        <span class="rubr">
                        <a href="/news/politics-economics/">{{ $categoryName }}</a></span>
                    </div> 
                    {{-- <hr class="onFoot"> --}}
                    <div class="clear"></div>
                    
                </div>
            @endforeach
                <div class="clear"></div>
                @include('site.pagination')
                <div class="clear"></div>
            </div>    
        </div>      
        <div class="PeachSolidBlock650_bottom"></div>
    </div>

    @include('site.rand_news_in_category')
</div>

<div class="clear"></div>
@endsection