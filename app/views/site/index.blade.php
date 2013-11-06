@extends('site.main')

@section('content')

<div class="content">    
<div id="contentTopBlock">
    
    <div id="mainNewsFlash">        
        <div class="BlueBlock390 ml0">
        <div class="BlueBlock390_top"></div>
            <div class="BlueBlock390_header">Главные новости</div>
            <div class="BlueBlock390_cont">
            

            <div id="featuredWrap">
                <div id="featured" >
                
                    @include('helpers.mainNewsSlider')

                </div>
            </div>

    
            
        </div>      
        <div class="BlueBlock390_bottom"></div>
    </div>  
</div>
  
 

<div id="contentTopBlockLeft">  
<div class="PinkBlock240 mr0">
        <div class="PinkBlock240_top"></div>
            <div class="PinkBlock240_header">Будь в курсе событий</div>

            <div class="PinkBlock240_cont pb5">
            
                <ul id="vUdobnomFormate" class="nashiNovosti budVkurse">
                    <li id="rss"><a href="http://twitter.com/#!/meksika24/">Twitter</a></li>
                    <li id="yandex"><a href="http://www.yandex.ru/?add=65935&amp;from=promocode">Я.Виджет</a></li>
                </ul>

                <div class="clear"></div>
            </div>      
        <div class="PinkBlock240_bottom"></div>

        </div>

{{-- 
<div class="GrayBlock240 mr0">
<div class="GrayBlock240_top"></div>
<div class="GrayBlock240_header">Погода</div>
<div class="GrayBlock240_cont pb5">
            
<span id="gradus">+16°C</span>
<div id="weather">
<img id="weather1" alt="" src="/images/site/main/sunly.png">
<img id="weather2" alt="" src="/images/site/main/none.png">
</div>
<span id="city">Мехико</span>
<div class="clear"></div>
            
</div>      
<div class="GrayBlock240_bottom"></div>
</div>
--}}
  
            
            
    </div>  
</div>  
        
        
    @foreach ($previewBlocks as $key => $newsInCategory)    
    <div class="PeachBlock316 @if(($key%2) == 0 ) left @else right @endif">
        <a class="news_rss" href="/rss/"><img src="images/site/main/news_rss.png" alt=""></a> <div class="PeachBlock316_top"></div>
        <div class="PeachBlock316_header">{{ $newsInCategory['category_name'] }}</div>
        <div class="PeachBlock316_cont">
        @foreach ($newsInCategory['articles'] as $news)
            <div class="news">
                <img class="news-img-main" src="/{{ $news['preview'] }}" height="76" width="100" alt="">
                <h4><a href="/news/{{ $newsInCategory['category_alias'] }}/{{ $news['alias'] }}" title="{{ $news['article_name'] }}">
                    {{ $news['article_name'] }}</a></h4>
                <span class="newdate">{{ $news['created_at'] }}</span>
                <div class="clear"></div>
            </div>
        @endforeach
            <a class="allNews" href="/news/politics-economics/">Все новости раздела</a>
        </div>
        <div class="PeachBlock316_bottom"></div>
    </div>
    @endforeach


</div>

<div class="clear"></div>


@endsection