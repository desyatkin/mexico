<?php

/*
|-------------------------------------------------------------------------------
| Контроллер управлющий выводом контента на сайт
|-------------------------------------------------------------------------------
| Необходимый функционал воспроизведен полностью.
|
| Не сделано: 
|   404-я ошибка (там сейчас заглушка)
|
| Примечание: 
|   Элементы выделенные комментариями: "// *!*!*!*!*!" нуждаются 
|   в более конкретном или подробном описании
|-------------------------------------------------------------------------------
|
| Фуннкции:
|   основные (отвечают за вывод контента):
|   getShowIndex()                                  - формирует главную страницу
|   getShowCategory($category, $subcategory)        - формирует страницу категории
|   getShowArticle($category, $subcategory, $year, $month, $day, $alias) - формирует страницу статьи
|     (Примечание: переменные $year $month $day - дата создания статьи)
|   
| вспомогательные (повторяющиеся операции, вывод ошибок, etc...):
|
|   blockArticles($limit, $order)                   - несколько случайных или последних статей 
|                                                     (безотносительно категории)
|
|   articleFromCategory($categoryId, $subcategoryId, $limit) - несколько случайных
|                                                   статей из конкретной категории
|   error404() - ошибка 404 NotFound
|
| ВАЖНО!!! Все переменные передающиеся во view являются обязательными! 
| Прежде чем удалять их от сюда необходимо убрать их в соответствующем шаблоне 
| в папке /app/views/
|
|-------------------------------------------------------------------------------
| Шаблоны с указанием переменных которые необходимо передать
| site.index:
|   newsOfTheWeek       - 1 случайная статья для блока "Новость недели" (сайдбар)
|   lastNews            - массив последних статей для блока "Последние новости" (сайдбар)
|  (примечание: количество статей варьируется в зависимости от шаблона и может быть любым)
|   interestingArticle  - 1 случайная статья для блока "Интересные статьи" (сайдбар)
|   randomArticles      - 4 случайных статьи для блока-слайдера в верхней части сайта
|   previewBlocks       - массив с блоками статей для анонсов по категориям
|  (примечание: этот блок содержит только статьи из подкадегорий категории "Новости")
|
| site.category
|   newsOfTheWeek       - 1 случайная статья для блока "Новость недели" (сайдбар)
|   interestingArticle  - 1 случайная статья для блока "Интересные статьи" (сайдбар)
|   lastNews            - массив последних статей для блока "Последние новости" (сайдбар)
|  (примечание: количество статей варьируется в зависимости от шаблона и может быть любым)
|   newsInCtaegory      - массив случайных статей из данной категории для блока "Новости по теме"
|   articles            - массив статей
|   pagination          - данные для формирования ссылок пагинации
|   categoryName        - имя категории
|   url                 - ссылка на категориию 
|                        (идет как префикс к ссылкам на статьи)
|
| site.article
|   newsOfTheWeek       - 1 случайная статья для блока "Новость недели" (сайдбар)
|   interestingArticle  - 1 случайная статья для блока "Интересные статьи" (сайдбар)
|   categoryName        - имя категории
|   url                 - ссылка на категориию 
|   lastNews            - массив последних статей для блока "Последние новости" (сайдбар)
|  (примечание: количество статей варьируется в зависимости от шаблона и может быть любым)
|   article             - массив со статьей
|   newsInCtaegory      - массив случайных статей из данной категории для блока "Новости по теме"
|
| Дополнительная информация:
|  Баннеры:
|   banners.banner_left     - баннер в левом сайдбаре
|   banners.banner_top      - баннер в шапке под меню
|  Хелперы:
|   helpers.do_you_know     - статичный html код, блок "Знаете ли вы, что?" в сайдбаре
|   helpers.mainNewsSlider  - собирает блок-слайдер для главной страницы
|   helpers.sape            - выводит ссылки сапы
|   helpers.sotmarket       - выводит баннер сотмаркета
|-------------------------------------------------------------------------------
*/
class SiteController extends \BaseController {

    //------------------------------------------------------------------------------
    // Основные методы
    //------------------------------------------------------------------------------
    /*
    |-------------------------------------------------------------------------------
    | Показывает главную страницу сайта
    |-------------------------------------------------------------------------------
    | возвращает:
    |   $view - сформированная страница
    |
    | дополнительные функции:
    |   blockArticles($limit)  - возвращает массив случайных статей 
    |                            для блока анонсов в шапке
    | переменные:
    |   $saidbarArticles  - массив случайных статей для блоков "Новость недели" и "Интересные статьи"
    |   $randomArticles   - массив случайных статей для блока-слайдера в верхней части страницы
    |   $lastNews         - массив последних статей на сайте
    |   $otherCategories  - массив подкатегорий категории news
    |   $previewBlocks    - массив данных для блоков анонсов по категориям
    |-------------------------------------------------------------------------------
    */
    public function getShowIndex() {

        // Получаем 2 случайных статьи для блоков в сайдбаре
        $saidbarArticles = $this->blockArticles(2);

        // Получаем 4 случайных статьи для блока-слайдера в верхней части главной страницы
        $randomArticles = $this->blockArticles(4);

        // Получаем массив всех подкатегорий категории news
        $otherCategories = Categories::select('id', 'alias', 'category_name')
                                     ->where('parent_id', '=', 1)
                                     ->get()
                                     ->toArray();
        
        $i = 0;
        $previewBlocks = array();

        // перебираем массив категории 
        // и для каждого элемента берем по 2 последних статьи.
        // Формируем новый массив previewBlocks содержащий псевдонимы категорий(alias)
        // и выбранные из них статьи
        foreach ($otherCategories as $category) {
            $previewArticles = Articles::where('subcategory_id', '=', $category['id'])
                                       ->limit(2)
                                       ->orderBy('id', 'DESC')
                                       ->get()
                                       ->toArray();

            $previewBlocks[$i]['category_alias'] = $category['alias'];
            $previewBlocks[$i]['category_name']  = $category['category_name'];
            $previewBlocks[$i]['articles']       = $previewArticles;
            $i++;
        }
        
        // берем 8 последних статей на сайте для блока в сайдбаре
        $lastNews = $this->blockArticles(8, 'id DESC');


        // отправляем все переменные во view
        $view = View::make('site.index')
                    ->with('newsOfTheWeek', array_shift($saidbarArticles))
                    ->with('lastNews', $lastNews)
                    ->with('interestingArticle', array_shift($saidbarArticles))
                    ->with('randomArticles', $randomArticles)
                    ->with('previewBlocks', $previewBlocks);

        // Возвращаем сформированную страницу
        return $view;
    }

    /*
    |-------------------------------------------------------------------------------
    | Показывает категорию
    |-------------------------------------------------------------------------------
    | принимает:
    |   $category   - псевдоним (alias) категории
    | возвращает:
    |   $view       - сформированная страница
    |
    | дополнительные функции:
    |   blockArticles($limit) - возвращает массив случайных статей 
    |                           для блока анонсов в шапке
    |   error404()            - выдает ошибку 404 not found
    |
    | переменные:
    |   $categoryArray         - массив содержащий id категории и её имя
    |   $articlesAndPagination - массив содержащий блок статей из категории 
    |                            и данные необходимые для организации пагинации
    |   $articles              - часть массива articlesAndPagination содержащая
    |                            только блок статей
    |   $pagination            - часть массива articlesAndPagination содержащая
    |                            только данные для пагинации
    |   $saidbarArticles       - массив случайных статей для блоков "Новость недели" и "Интересные статьи"
    |   $lastNews              - массив последних статей на сайте
    |   $newsInCtaegory        - массив случайных статей из данной категории
    |-------------------------------------------------------------------------------
    */
    public function getShowCategory($category, $subcategory = false) {
        // ишем в базе корневую категорию по её псевдониму (alias)
        $categoryArray = Categories::select('id', 'category_name')
                                   ->where('alias', '=', $category)
                                   ->where('parent_id', '=', 0)
                                   ->get()
                                   ->toArray();

        // если категории нет -> 404
        if (empty($categoryArray)) 
            $this->error404();
        
        $categoryId     = $categoryArray[0]['id'];
        $categoryName   = $categoryArray[0]['category_name'];

        // если указана подкатегория (subcategory != false)
        if ($subcategory) {
            // пытаемся получить категорию с псевдонином $subcategory 
            // id родителя равным id корневой категории
            $subcategoryArray = Categories::select('id', 'category_name')
                                          ->where('alias', '=', $subcategory)
                                          ->where('parent_id', '=', $categoryArray[0]['id'])
                                          ->get()
                                          ->toArray();
            
            // если такой категории не существует -> 404
            if (empty($subcategoryArray)) {
                $this->error404();
            }
            
            // если категория существует переменной subcategoryId присваивается значение id подкатегории
            // и переменная categoryName меняется на имя подкатегории
            $subcategoryId = $subcategoryArray[0]['id'];
            $categoryName  = $subcategoryArray[0]['category_name'];
        } else {
            // если подкатегория не указана subcategoryId присваивается 0
            $subcategoryId = 0;
        }
        
        // ищем статьи из данной категории (по id категории с условием что id подкатегории равно 0)
        // (метод paginate добавит к полученному массиву элементы позволяющие легко построить пагинацию)
        $articlesAndPagination = Articles::where('category_id', '=', $categoryArray[0]['id'])
                                         ->where('subcategory_id', '=', $subcategoryId)
                                         ->orderBy('id', 'DESC')
                                         ->paginate(12)
                                         ->toArray();

        // если статей нет -> 404
        if (empty($articlesAndPagination['data'])) 
            $this->error404();

        // забираем из массива элемент со статьями (он последний)
        // оставшиеся элементы(данные для пагинации) для удобства восприятия 
        // отправляем в массив $pagination
        $articles               = array_pop($articlesAndPagination);
        $pagination             = $articlesAndPagination;

        // Получаем 2 случайных статьи для блоков в сайдбаре
        $saidbarArticles        = $this->blockArticles(2);
        // берем 5 последних статьи(безотносительно категории) для блока в сайдбаре
        $lastNews               = $this->blockArticles(5, 'id DESC');
        // берем 4 случайных статьи из текущей катигории
        $newsInCtaegory         = $this->articleFromCategory($categoryId, $subcategoryId, 4);


        // собираем link который будет префиксом для всех ссылок на статьи в данной категории
        $url = '/' . $category . '/';
        if ($subcategory) $url .= $subcategory . '/';

        // отправляем все переменные во view
        $view = View::make('site.category')
                    ->with('newsOfTheWeek'          , array_shift($saidbarArticles))
                    ->with('interestingArticle'     , array_shift($saidbarArticles))
                    ->with('lastNews'               , $lastNews)
                    ->with('newsInCtaegory'         , $newsInCtaegory)
                    ->with('articles'               , $articles)
                    ->with('pagination'             , $pagination)
                    ->with('categoryName'           , $categoryName)
                    ->with('url'                    , $url);

        // возвращаем сформированную страницу
        return $view;
    }


    /*
    |-------------------------------------------------------------------------------
    | Показывает статью
    |-------------------------------------------------------------------------------
    | принимает:
    |   $category           - псевдоним категории
    |   $subcategory        - псевдоним подкатегории
    |   $alias              - псевдоним статьи
    |   $year, $month, $day - переменные представляющие дату создания статьи, участвуют в формировании
    |                         ссылки на статью.
    |
    | возвращает:
    |   $view               - сформированная страница
    | дополнительные функции: 
    |   blockArticles($limit) - возвращает массив случайных статей 
    |                           для блока анонсов в шапке
    |   error404()            - выдает ошибку 404 not found
    |   articleFromCategory   - берет несколько произвольных статей
    |                           из заданной категории
    |-------------------------------------------------------------------------------
    */
    public function getShowArticle($category, $subcategory, $year = 0, $month = 0, $day = 0, $alias) {
        // ишем в базе корневую категорию по её псевдониму (alias)
        $categoryArray = Categories::select('id', 'category_name')
                                   ->where('alias', '=', $category)
                                   ->where('parent_id', '=', 0)
                                   ->get()
                                   ->toArray();
        // если категории нет -> 404
        if (empty($categoryArray)) 
            $this->error404();

        $categoryId   = $categoryArray[0]['id'];
        $categoryName = $categoryArray[0]['category_name'];

        // если указана подкатегория (subcategory != false)
        if ($subcategory) {
            // пытаемся получить категорию с псевдонином $subcategory 
            // id родителя равным id корневой категории
            $subcategoryArray = Categories::select('id', 'category_name')
                                          ->where('alias', '=', $subcategory)
                                          ->where('parent_id', '=', $categoryId)
                                          ->get()
                                          ->toArray();
            
            // если такой категории не существует, считаем что пользователь сошел с ума
            // и выдаем 404
            if (empty($subcategoryArray)) 
                $this->error404;

            // если категория существует переменной subcategoryId присваивается значение id подкатегории
            // и переменная categoryName меняется на имя подкатегории
            $subcategoryId = $subcategoryArray[0]['id'];
            $categoryName  = $subcategoryArray[0]['category_name'];
        } else {
            // если подкатегория не указана subcategoryId присваивается 0
            $subcategoryId = 0;
        }

        // ищем статью из данной категории/подкатегории
        $article = Articles::where('alias', '=', $alias)
                           ->where('category_id', '=', $categoryId)
                           ->where('subcategory_id', '=', $subcategoryId)
                           ->orderBy('id', 'DESC')
                           ->limit(1)
                           ->get()
                           ->toArray();

        // если статьи нет -> 404
        if (empty($article)) 
            $this->error404();

        
        // проверка даты
        // данные из ссылки на страницу должны совпадать с датой создания этой страницы
        // если не совпадают -> 404
        $date = $year . '-' . $month . '-' . $day;
        if (strval($article[0]['created_at']) != strval($date))
            $this->error404();

        // берем 2 случайных статьи для блоков в сайдбаре
        $saidbarArticles        = $this->blockArticles(2);
        // берем 5 последних статьи(безотносительно категории) для блока в сайдбаре
        $lastNews               = $this->blockArticles(5, 'id DESC');
        // берем 5 случайных статьи из текущей катигории
        $newsInCtaegory         = $this->articleFromCategory($categoryId, $subcategoryId, 5);




        // собираем ссылку на категорию в которой находится статья
        $url = '/' . $category . '/';
        if ($subcategory) $url .= $subcategory . '/';

        // отправляем все переменные во view
        $view = View::make('site.article')
                    ->with('newsOfTheWeek'          , array_shift($saidbarArticles))
                    ->with('interestingArticle'     , array_shift($saidbarArticles))
                    ->with('categoryName'           , $categoryName)
                    ->with('url'                    , $url)
                    ->with('lastNews'               , $lastNews)
                    ->with('article'                , $article)
                    ->with('newsInCtaegory'         , $newsInCtaegory);

        // возвращаем сформированную страницу
        return $view;
    }


    public function getShowStatic($alias) {
        $categoryId     = 9;
        $subcategoryId  = 0;
       
        // ищем статью из данной категории/подкатегории
        $article = Articles::where('alias', '=', $alias)
                           ->where('category_id', '=', $categoryId)
                           ->where('subcategory_id', '=', $subcategoryId)
                           ->orderBy('id', 'DESC')
                           ->limit(1)
                           ->get()
                           ->toArray();

        // если статьи нет -> 404
        if (empty($article)) 
            $this->error404();
        
        // берем 2 случайных статьи для блоков в сайдбаре
        $saidbarArticles        = $this->blockArticles(2);
        // берем 5 последних статьи(безотносительно категории) для блока в сайдбаре
        $lastNews               = $this->blockArticles(5, 'id DESC');
        // берем 5 случайных статьи из текущей катигории
        $newsInCtaegory         = $this->articleFromCategory($categoryId, $subcategoryId, 5);




        // собираем ссылку на категорию в которой находится статья
        $url = '/pages/';
        

        // отправляем все переменные во view
        $view = View::make('site.article')
                    ->with('newsOfTheWeek'          , array_shift($saidbarArticles))
                    ->with('interestingArticle'     , array_shift($saidbarArticles))
                    ->with('categoryName'           , '')
                    ->with('url'                    , $url)
                    ->with('lastNews'               , $lastNews)
                    ->with('article'                , $article)
                    ->with('newsInCtaegory'         , $newsInCtaegory);

        // возвращаем сформированную страницу
        return $view;
    }


    //------------------------------------------------------------------------------
    // Вспомогательные методы
    //------------------------------------------------------------------------------
    /*
    |-------------------------------------------------------------------------------
    | Выбрать последние статьи для блока "ВидеоНовости"
    |-------------------------------------------------------------------------------
    | принимает:
    |   $limit    - количество статей
    | возвращает:
    |   $articles - массив статей из категории "ВидеоНовости"
    |-------------------------------------------------------------------------------
    */
    public function getVideoNews($limit){
        $articles = Articles::where('category_id', '=', 18)
                            ->limit($limit)
                            ->orderBy('id', 'DESC')
                            ->get()
                            ->toArray();
        return $articles;
    }
    
    /*
    |-------------------------------------------------------------------------------
    | Ошибка 404 not found
    |-------------------------------------------------------------------------------
    */
    public function error404(){
        echo '<center><div style="font-size:70px; margin: 10% 0;">404<br> Not Found</div></center>';
        die();
    }

    /*
    |-------------------------------------------------------------------------------
    | Выбрать случайные или последние статьи для блока анонсов в шапке
    |-------------------------------------------------------------------------------
    | принимает:
    |   $limit    - количество статей
    |   $order    - сортировка (случайные или последние)
    | возвращает:
    |   $articles - переработанный массив статей
    |-------------------------------------------------------------------------------
    */
    public function blockArticles($limit, $order = 'RAND()'){
        // Берем несколько случайных или последних статей
        //(количество статей определяется переменной limit)
        $articles =  Articles::select('id', 'category_id', 'subcategory_id', 'alias', 'article_name', 'content', 'preview', 'created_at')
                             ->whereRaw('`id`>0 order by ' . $order)
                             ->limit($limit)
                             ->get()
                             ->toArray();

        // Изменяем-дополняем полученый массив
        foreach ($articles as &$element) {
            // Собираем ссылку на статью и добавляем её в массив
            // Добавляем к ссылке alias категории
            $url =  '/' . implode(Categories::select('alias')->find($element['category_id'])->toArray()) . '/';
            
            // Если статья находится не в корневой категории то добавляем alias подкатегории к ссылке
            $subcategory_alias = Categories::select('alias')->find($element['subcategory_id']);
            if (!is_null($subcategory_alias)) $url .= implode($subcategory_alias->toArray()) . '/';
            
            // *!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!
            // ссылка на конкретную страницу должна содержать в себе дату её создания
            $url .= str_replace('-', '/', $element['created_at']) . '/';
            // *!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!*!

            // Добавляем к ссылке alias статьи          
            $url .= $element['alias'];

            // Изменяем исходный массив (добавляем элемент с ключем "url")
            $element['url'] = $url;
            
            // Обрезаем текст статьи для анонса
            $element['content'] = mb_substr($element['content'], 0, 110) . '...';

            // Обрезаем слишком длинные заголовки 
            //(заголовки должны умещаться в одну строку иначе ломается верстка)
            if (mb_strlen($element['article_name']) > 55)
                $element['article_name'] = mb_substr($element['article_name'], 0, 55) . '...';
        }

        // возвращаем обработанный массив статей
        return $articles;
    }

    /*
    |-------------------------------------------------------------------------------
    | Выбрать случайные статьи из категории
    |-------------------------------------------------------------------------------
    | принимает:
    |   $categoryId     - id категории
    |   $subcategoryId  - id подкатегории
    |   $limit          - количество статей
    | возвращает:
    |   articles        - массив со статьями
    |-------------------------------------------------------------------------------
    */
    public function articleFromCategory($categoryId, $subcategoryId, $limit) {
        $articles = Articles::whereRaw('category_id = ' . $categoryId . ' AND subcategory_id = ' . $subcategoryId . ' order by RAND()')
                           ->limit($limit)
                           ->get()
                           ->toArray();

        return $articles;
    }

    /*
    |-------------------------------------------------------------------------------
    | Generate article url from id
    |-------------------------------------------------------------------------------
    */
    public function getArticleURL ($id) {

        //get article
        $article = Articles::find($id);

        // compose url
        $url = 'http://mexico24.ru/' . $article->category->alias;
        if( isset($article->subcategory->alias) ) $url .= '/'. $article->subcategory->alias;
        $url .= '/' . date('Y/m/d', strtotime($article->created_at)) . '/'. $article->alias;

        return $url;

    }

    /*
    |-------------------------------------------------------------------------------
    | RSS для яндекс новостей
    |-------------------------------------------------------------------------------
    */
    public function getRSS() {

        header("Content-Type:   application/rss+xml");


        // description protocol, open xml document
        echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"
        . '<rss version="2.0" xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru">' . "\n"
        . '<channel>' . "\n"
        . '<title>Мексика 24</title>' . "\n"
        . '<link>http://mexico24.ru/</link>' . "\n"
        . '<description>Актуальные новости и интересные статьи.</description>' . "\n"
        . '<image>' . "\n"
        . '<url>http://mexico24.ru/images/site/main/logo.gif</url>' . "\n"
        . '<title>Мексика 24</title>' . "\n"
        . '<link>http://mexico24.ru/</link>' . "\n"
        . '</image>' . "\n";


        // get 20 last articles
        $articles = Articles::orderBy('created_at', 'desc')->limit(20)->get();

        foreach($articles as $article) {

            echo '<item>' . "\n";
            echo '  <title>'. $article->article_name .'</title>' . "\n";
            echo '  <link>'. $this->getArticleURL($article->id) .'</link> . "\n"';
            echo '  <category>Политика и экономика</category>' . "\n";
            echo '  <enclosure url="http://mexico24.ru/userfiles/'. $article->preview .'" type="image/jpeg"/>' . "\n";
            echo '  <pubDate>'. date( 'r', strtotime($article->created_at) ) .'</pubDate>' . "\n";
            echo '  <yandex:genre>message</yandex:genre>' . "\n";
            echo '  <yandex:full-text>'. htmlspecialchars(strip_tags($article->description)) .'</yandex:full-text>' . "\n";
            echo '</item>' . "\n";
        }

        // close xml document
        echo '</channel>
              </rss>';
    }


    /*
    |-------------------------------------------------------------------------------
    | function generate sitemap
    |-------------------------------------------------------------------------------
    */
    public function getSitemap() {

        header("Content-Type:   application/xml");

        // open xml document
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation=" http://www.sitemaps.org/schemas/sitemap/0.9">';

        $articles = Articles::all();

        foreach($articles as $article) {
            echo '<url>' . "\n";

            echo '<loc>'. $this->getArticleURL($article->id) .'</loc>' . "\n";
            echo '<priority>0.5</priority>' . "\n";
            echo '<lastmod>'. $article->updated_at .'</lastmod>' . "\n";

            echo '</url>' . "\n";
        }

        //close xml document
        echo '</urlset>';
    }


    /*
    |-------------------------------------------------------------------------------
    | function make redirect to other url
    |-------------------------------------------------------------------------------
    */
    public function getRedirects() {
        return Redirect::to( Input::get('url') );
    }

    /*
    |-------------------------------------------------------------------------------
    | Функция разового использования. 
    | Удаляет слеш в начале ссылки на превью картинки
    | для использования - разкоментировать и добавить соответствующий роут
    |-------------------------------------------------------------------------------
    */
    public function getDeleteSlashes()
    {
        $articles= Articles::all();
        foreach ($articles as $article) {
            if(mb_substr($article->preview, 0, 1) == '/'){
                $article->preview = mb_substr($article->preview, 1);
                echo $article->preview . "<br> \n";
                $article->save();
            }
        }
    
    }
}