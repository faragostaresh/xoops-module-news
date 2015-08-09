<?php

function news_breaking_show($options) {
    $story_handler = xoops_getmodulehandler('story', 'news');
    $story = $story_handler->News_StoryBreaking();
    $block = array();
    $block['contents'] = $story;
    return $block;
}

function news_breaking_edit($options) {}