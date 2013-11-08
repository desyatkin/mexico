@extends('site.main')

@section('content')

<div class="content">        
    <div class="PeachSolidBlock650 ml0">
    <div class="PeachSolidBlock650_top"></div>
        <div class="PeachSolidBlock650_cont">
        
        @foreach ($article as $element)    
            <h1>{{ $element['article_name'] }}</h1>

            <div class="NewsInfoBlock">
                <span class="date">{{ $element['created_at'] }}</span>
                <span class="rubr">
                    <a href="{{ $url }}">{{ $categoryName }}</a>
                </span>
                <div class="clear"></div>
            </div>

            <div class="newsBl">
                <div class="Img"> 
                    <img src="/{{ $element['preview'] }}" width="240" height="180" alt="">
                    <p class="Title">{{ $element['article_name'] }}</p>
                </div>

                <strong>{{ $element['description'] }}</strong>
                {{ $element['content'] }}
                <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
                <div class="yashare-auto-init" data-yasharel10n="ru" data-yasharetype="none" data-yasharequickservices="yaru,vkontakte,facebook,twitter"></div>

                <div class="clear"></div>
            </div>
        @endforeach
        </div>
        <div class="PeachSolidBlock650_bottom"></div>
    </div>    
    @include('site.rand_news_in_category')
</div>
<div class="clear"></div>
@endsection