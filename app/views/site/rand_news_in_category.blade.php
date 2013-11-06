<div class="BlueBlock650 ml0">
    <div class="BlueBlock650_top"></div>
    <div class="BlueBlock650_header">Новости по теме</div>
    <div class="BlueBlock650_cont">
    @foreach($newsInCtaegory as $news)
        <div class="otherNews">
            <div class="date">
                <nobr>{{ $news['created_at'] }}</nobr>
            </div>
            <div class="text">
                <a href="{{ $url }}{{ str_replace('-', '/', $news['created_at']) }}/{{ $news['alias'] }}/">
                {{ $news['article_name'] }}</a>
            </div>
            <div class="clear"></div>
        </div>
    @endforeach
    </div>
    <div class="BlueBlock650_bottom"></div>
</div>