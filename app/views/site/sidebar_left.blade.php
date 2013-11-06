<div class="left">     
    {{-- Новость недели --}}    
    <div class="PinkBlock310 ml0">
        <div class="PinkBlock310_top"></div>
        <div class="PinkBlock310_header">Новость недели</div>
        <div class="PinkBlock310_cont">
            <div class="newsOfaWeek">
                <img src="{{ $newsOfTheWeek['preview'] }}" height="76" width="100" alt="">
                <h4>
                    <a href="{{ $newsOfTheWeek['url'] }}">
                    {{ $newsOfTheWeek['article_name'] }}</a>
                </h4>
                <div class="clear"></div>
            </div>
        </div>
        <div class="PinkBlock310_bottom"></div>
    </div>

    <div style="clear:both;"></div><br>

    @include('banners.banner_left')

    <div class="PinkBlock310 ml0">
        <div class="PinkBlock310_top"></div>
        <div class="PinkBlock310_header">Реклама</div>
        <div class="PinkBlock310_cont">
            @include('helpers.sape')
            <div class="clear">
        </div>
    </div>
    <div class="PinkBlock310_bottom"></div>
    </div>

    <div class="GrayBlock310 ml0">
        <div class="GrayBlock310_top"></div>
        <div class="GrayBlock310_header">Последние новости</div>
        <div class="GrayBlock310_cont">
            <ul>
            @foreach($lastNews as $news)
                <li>
                    <a href="{{ $news['url'] }}">{{ $news['article_name'] }}</a>
                    <br>{{ $news['content'] }}
                    <span class="date"><nobr>{{ $news['created_at'] }}</nobr></span>
                </li>
            @endforeach
            </ul>
        </div>
        <div class="GrayBlock310_bottom"></div>
    </div>

    @include('helpers.do_you_know')

    <div class="PinkBlock310 ml0">
        <div class="PinkBlock310_top"></div>
        <div class="PinkBlock310_header">Интересные статьи</div>
        <div class="PinkBlock310_cont">
            <div class="newsOfaWeek">
                <img src="{{ $interestingArticle['preview'] }}" height="76" width="100" alt="">
                <h4><a href="{{ $interestingArticle['url'] }}">{{ $interestingArticle['article_name'] }}</a></h4>
                <div class="clear"></div>
            </div>
        </div>
        <div class="PinkBlock310_bottom"></div>
    </div>

    @include('helpers.sotmarket')
                   
    <div class="GrayBlock310 ml0">
        <div class="GrayBlock310_top"></div>

        <div class="GrayBlock310_header">Наши новости на вашем сайте</div>
        <div class="GrayBlock310_cont ie7ml0">
            <ul id="vUdobnomFormate" class="nashiNovosti">
                <li id="rss"><a href="/rss/">RSS лента</a></li>
                <li id="informer"><a href="/pages/informer/">Информер</a></li>
            </ul>
            <div class="clear"></div>

        </div>      
        <div class="GrayBlock310_bottom"></div>
    </div>  
</div>