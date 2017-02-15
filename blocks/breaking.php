<?php

function news_breaking_show($options) {

    require_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/modules/news/class/perm.php';
    require_once XOOPS_ROOT_PATH . '/modules/news/class/utils.php';

    $story_handler = xoops_getmodulehandler('story', 'news');
    $story = $story_handler->News_StoryBreaking();
    $block = array();
    $block['contents'] = $story;
    $block['count'] = count($story);
    return $block;
}

function news_breaking_edit($options) {}