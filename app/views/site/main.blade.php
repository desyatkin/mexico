<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name='yandex-verification' content='65d29bfe2194b660' />
    <meta name="description" content=""></meta>
    <link rel="shortcut icon" href="/favicon.ico" />
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="/js/jquery-1.4.3.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>
    <script type="text/javascript" src="/js/ui/jquery.ui.core.js"></script>
    <script type="text/javascript" src="/js/ui/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="/js/ui/jquery.ui.tabs.js"></script>
    <script src="/js/functions.js" type="text/javascript"></script>


    <link rel="stylesheet" type="text/css" href="/css/all.css">
    <link rel="stylesheet" type="text/css" href="/css/tango/skin.css" />
    <link rel="stylesheet" type="text/css" href="/css/slider.css" />

</head>
<body>
    <div class="wrapper">
        @include('site.top')

        @include('site.sidebar_left')

        @yield('content')

        @include('site.footer')

    </div>

</body>
</html>