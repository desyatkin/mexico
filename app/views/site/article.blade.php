@extends('site.main')

@section('content')

<div class="content">        
    <div class="PeachSolidBlock650 ml0">
    <div class="PeachSolidBlock650_top"></div>
        <div class="PeachSolidBlock650_cont">
        
        {{-- var_dump($article) --}}
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
                    <img src="{{ $element['preview'] }}" width="240" height="180" alt="">
                    <p class="Title">{{ $element['article_name'] }}</p>
                </div>

                <strong>{{ $element['description'] }}</strong>
                {{ $element['content'] }}
                
                <div>В ней речь идет о том, что отныне добыча полезных ископаемых, таких как газ и нефть перестанут быть только национальным делом. На протяжении многих лет, на мексиканском рынке нефти господствовала лишь одна компания – Пемекс. Из-за того, что компания работала в одиночку без каких-либо инвестиций, она стала убыточной. Но в новом законопроекте, Пемекс заработал право привлекать к разработке и добыче нефти иностранных спонсоров, а нефть при этом должна оставаться в стране.</div>
                <div>&nbsp;</div>
                <div>Однако, как оказалось, не все довольны таким ходом дел. Мексиканская оппозиция не хочет впускать к своим деньгам и своей нефти чужаков. В поддержку решения правительства, с оппозицией недавно встретился генеральный директор Пемекса Карлос Моралес. Он объяснил, что от реформы выигрывает не только его компания, но и вся страна в целом, поскольку так счета за энергию в стране станут намного меньше, а нефть будет добываться более быстро и эффективно благодаря инвестициям в оборудование.</div>

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