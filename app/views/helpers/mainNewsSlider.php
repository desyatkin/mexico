<?php
/* Этот helper собирает html для бока-слайдера "Главные Новости" на главной странице.
Вынос данного действия в отдельный модуль обусловлен необходимостью написать полноценный php скрипт
который позволит собрать блок в одном цикле */

// собираем в переменные элементы слайдера
$slider = '';
$mainBlock = '';
foreach ($randomArticles as $key => $article) {
    $slider .= '<li class="ui-tabs-nav-item ';
    $slider .= '" id="nav-fragment-' . ($key+1) . '">'
             . '<a href="#fragment-' . ($key+1) . '">'
             . '<img src="/' . $article['preview'] . '" width="81" height="62" alt="" />'
             . '</a>'
             . '</li>';

    $mainBlock .= '<div id="fragment-' . ($key+1) .'" class="ui-tabs-panel';
    $mainBlock .= '" style="">'
                . '<img src="/' . $article['preview'] . '" width="273" height="218" alt="" />'
                . '<div class="info" >'
                . '<p><a href="' . $article['url'] . '">' . $article['article_name'] . '</a></p>'
                . '</div>'
                . '</div>';
}

// выводим
echo '<ul class="ui-tabs-nav">' . $slider . '</ul>' . $mainBlock; 