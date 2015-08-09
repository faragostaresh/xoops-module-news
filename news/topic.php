<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * News topic file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

// Include module header
require dirname(__FILE__) . '/header.php';
// Include content template
$xoopsOption ['template_main'] = 'news_topic.html';
// include Xoops header
include XOOPS_ROOT_PATH . '/header.php';
// Add Stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/news/css/style.css');

// get limited information
if (isset($_REQUEST['limit'])) {
    $topic_limit = NewsUtils::News_UtilityCleanVars($_REQUEST, 'limit', 0, 'int');
} else {
    $topic_limit = xoops_getModuleOption('admin_perpage_topic', 'news');
}

// get start information
if (isset($_REQUEST['start'])) {
    $topic_start = NewsUtils::News_UtilityCleanVars($_REQUEST, 'start', 0, 'int');
} else {
    $topic_start = 0;
}

$newscountbytopic = $story_handler->News_StoryCountByTopic();
$topics = $topic_handler->News_TopicList($topic_limit, $topic_start, $newscountbytopic);
$topic_numrows = $topic_handler->News_TopicCount();

if ($topic_numrows > $topic_limit) {
    $topic_pagenav = new XoopsPageNav($topic_numrows, $topic_limit, $topic_start, 'start', 'limit=' . $topic_limit);
    $topic_pagenav = $topic_pagenav->renderNav(4);
} else {
    $topic_pagenav = '';
}

if (xoops_getModuleOption('img_lightbox', 'news')) {
    // Add scripts
    $xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
    $xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.lightbox.js');
    // Add Stylesheet
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/lightbox.css');
    $xoopsTpl->assign('img_lightbox', true);
}

// breadcrumb
if (xoops_getModuleOption('bc_show', 'news')) {
    $breadcrumb = NewsUtils::News_UtilityBreadcrumb('topic.php', _NEWS_MD_TOPICS, 0, ' &raquo; ');
    $xoopsTpl->assign('breadcrumb', $breadcrumb);
}

$xoopsTpl->assign('topics', $topics);
$xoopsTpl->assign('topic_pagenav', $topic_pagenav);
$xoopsTpl->assign('advertisement', xoops_getModuleOption('advertisement', 'news'));
$xoopsTpl->assign('imgwidth', xoops_getModuleOption('imgwidth', 'news'));
$xoopsTpl->assign('imgfloat', xoops_getModuleOption('imgfloat', 'news'));

// include Xoops footer
include XOOPS_ROOT_PATH . '/footer.php';
?>