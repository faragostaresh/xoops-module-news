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
 * News index file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

// Include module header
include_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XOOPS_ROOT_PATH . '/modules/news/class/perm.php';
require_once XOOPS_ROOT_PATH . '/modules/news/class/utils.php';

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header("Content-type:text/xml");
$xoopsTpl = new XoopsTpl();
$xoopsTpl->caching = 2;
$xoopsTpl->cache_lifetime = 3600;
$myts = MyTextSanitizer::getInstance();
if (!$xoopsTpl->is_cached('db:news_rss.html')) {
    // Check if ML Hack is installed, and if yes, parse the $story in formatForML
    if (method_exists($myts, 'formatForML')) {
        $xoopsConfig['sitename'] = $myts->formatForML($xoopsConfig['sitename']);
        $xoopsConfig['slogan'] = $myts->formatForML($xoopsConfig['slogan']);
        $channel_category = $myts->formatForML('news');
    }
    $xoopsTpl->assign('channel_charset', _CHARSET);
    $xoopsTpl->assign('docs', 'http://cyber.law.harvard.edu/rss/rss.html');
    $xoopsTpl->assign('channel_title', htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
    $xoopsTpl->assign('channel_link', XOOPS_URL . '/');
    $xoopsTpl->assign('channel_desc', htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES));
    $xoopsTpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $xoopsTpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
    $xoopsTpl->assign('channel_editor', $xoopsConfig['adminmail']);
    $xoopsTpl->assign('channel_category', htmlspecialchars($channel_category));
    $xoopsTpl->assign('channel_generator', 'news');
    $xoopsTpl->assign('channel_language', _LANGCODE);
    $xoopsTpl->assign('image_url', XOOPS_URL . xoops_getModuleOption('rss_logo', 'news'));
    $dimention = getimagesize(XOOPS_ROOT_PATH . xoops_getModuleOption('rss_logo', 'news'));

    if (empty($dimention[0])) {
        $width = 140;
        $height = 140;
    } else {
        $width = ($dimention[0] > 140) ? 140 : $dimention[0];
        $dimention[1] = $dimention[1] * $width / $dimention[0];
        $height = ($dimention[1] > 140) ? $dimention[1] * $dimention[0] / 140 : $dimention[1];
    }

    $xoopsTpl->assign('image_width', $width);
    $xoopsTpl->assign('image_height', $height);

    if (isset($_REQUEST["topic"])) {
        $story_topic = NewsUtils::News_UtilityCleanVars($_REQUEST, 'topic', 0, 'int');
        $topics = '';
    } else {
        $topic_handler = xoops_getmodulehandler('topic', 'news');
        $topics = $topic_handler->getall($story_topic);
    }

    $story_infos = array(
        'topics' => $topics,
        'story_limit' => xoops_getModuleOption('rss_perpage', 'news'),
        'story_topic' => $story_topic,
        'story_start' => 0,
        'story_order' => 'DESC',
        'story_sort' => 'story_publish',
        'story_status' => '1',
        'story_static' => true,
        'admin_side' => false
    );

    $story_handler = xoops_getmodulehandler('story', 'news');
    $stores = $story_handler->News_StoryList($story_infos);

    $xoopsTpl->assign('contents', $stores);
}
$xoopsTpl->display('db:news_rss.html');
?>