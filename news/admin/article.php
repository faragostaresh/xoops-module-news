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
 * News Admin page
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

require dirname(__FILE__) . '/header.php';

include_once XOOPS_ROOT_PATH . "/class/pagenav.php";

// Display Admin header
xoops_cp_header();
// Define default value
$op = NewsUtils::News_UtilityCleanVars($_REQUEST, 'op', '', 'string');
// Initialize content handler
$topic_handler = xoops_getmodulehandler('topic', 'news');
$story_handler = xoops_getmodulehandler('story', 'news');

// Define scripts
$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
$xoTheme->addScript('browse.php?modules/news/js/admin.js');

// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/news/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/ui/' . xoops_getModuleOption('jquery_theme', 'system') . '/ui.all.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

// get module configs
$story_perpage = xoops_getModuleOption('admin_perpage', 'news');
 
// get user id content 
if (isset($_REQUEST["user"])) {
   $story_user = NewsUtils::News_UtilityCleanVars($_REQUEST, 'user', 0, 'int');
} else {
   $story_user = null;
}

// get limited information
if (isset($_REQUEST['limit'])) {
   $story_limit = NewsUtils::News_UtilityCleanVars($_REQUEST, 'limit', 0, 'int');
} else {
   $story_limit = $story_perpage;
}

// get start information
if (isset($_REQUEST['start'])) {
   $story_start = NewsUtils::News_UtilityCleanVars($_REQUEST, 'start', 0, 'int');
} else {
   $story_start = 0;
}
   
// get topic information
if (isset($_REQUEST['topic'])) {
   $story_topic = NewsUtils::News_UtilityCleanVars($_REQUEST, 'topic', 0, 'int');
   if ($story_topic) {
       $topics = $topic_handler->getall($story_topic);
       $topic_title = NewsTopicHandler::News_TopicFromId ( $story_topic );
   } else {
       $topics = $topic_title = _NEWS_AM_STORY_STATICS;
   }
} else {
   $story_topic = null;
   $topic_title = null;
   $topics = $topic_handler->getall($story_topic);
}
                     
switch ($op)
{
    case 'new_content':
        $story_type = NewsUtils::News_UtilityCleanVars($_REQUEST, 'story_type', 'news', 'string');
        $obj = $story_handler->create();
        $obj->News_StoryForm($story_type);
        break;

    case 'edit_content':
        $story_id = NewsUtils::News_UtilityCleanVars($_REQUEST, 'story_id', 0, 'int');
        if ($story_id > 0) {
            $obj = $story_handler->get($story_id);
            $obj->News_StoryForm();
        } else {
            NewsUtils::News_UtilityRedirect('article.php', 1, _NEWS_AM_MSG_EDIT_ERROR);
        }
        break;

    case 'delete':
        $story_id = NewsUtils::News_UtilityCleanVars($_REQUEST, 'story_id', '0', 'int');
        if ($story_id > 0) {
            $story = $story_handler->get($story_id);
            // Prompt message
            NewsUtils::News_UtilityMessage('backend.php', sprintf(_NEWS_AM_MSG_DELETE, $story->getVar('story_type') . ': "' . $story->getVar('story_title') . '"'), $story_id, 'content');
            // Display Admin footer
            xoops_cp_footer();
        }
        break;
        
    case 'expire':
    
        $story_infos = array(
            'topics' => $topics,
            'story_limit' => $story_limit,
            'story_topic' => $story_topic,
            'story_user' => $story_user,
            'story_start' => $story_start,
            'story_status' => 1,
            'story_static' => false,
        );
        
        $stores = $story_handler->News_StoryExpireList($story_infos);
        $story_numrows = $story_handler->News_StoryExpireCount($story_infos);
        
        if ($story_numrows > $story_limit) {
            $story_pagenav = new XoopsPageNav($story_numrows, $story_limit, $story_start, 'start', 'limit=' . $story_limit . '&op=offline');
            $story_pagenav = $story_pagenav->renderNav(4);
        } else {
            $story_pagenav = '';
        }

        $xoopsTpl->assign('navigation', 'content');
        $xoopsTpl->assign('navtitle', _NEWS_MI_CONTENT);
        $xoopsTpl->assign('topic_title', $topic_title);
        $xoopsTpl->assign('contents', $stores);
        $xoopsTpl->assign('story_pagenav', $story_pagenav);
        $xoopsTpl->assign('xoops_dirname', 'news');

        // Call template file
        $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/news/templates/admin/news_article.html');
        
        break;
         
    case 'offline':

        $story_infos = array(
            'topics' => $topics,
            'story_limit' => $story_limit,
            'story_topic' => $story_topic,
            'story_user' => $story_user,
            'story_start' => $story_start,
            'story_status' => 0,
            'story_static' => false,
        );

        $stores = $story_handler->News_StoryAdminList($story_infos);
        $story_numrows = $story_handler->News_StoryOfflineCount($story_infos);

        if ($story_numrows > $story_limit) {
            $story_pagenav = new XoopsPageNav($story_numrows, $story_limit, $story_start, 'start', 'limit=' . $story_limit . '&op=offline');
            $story_pagenav = $story_pagenav->renderNav(4);
        } else {
            $story_pagenav = '';
        }

        $xoopsTpl->assign('navigation', 'content');
        $xoopsTpl->assign('navtitle', _NEWS_MI_CONTENT);
        $xoopsTpl->assign('topic_title', $topic_title);
        $xoopsTpl->assign('contents', $stores);
        $xoopsTpl->assign('story_pagenav', $story_pagenav);
        $xoopsTpl->assign('xoops_dirname', 'news');

        // Call template file
        $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/news/templates/admin/news_article.html');
        
	    break;
	    
    default:

        $story_infos = array(
            'topics' => $topics,
            'story_limit' => $story_limit,
            'story_topic' => $story_topic,
            'story_user' => $story_user,
            'story_start' => $story_start,
            'story_status' => false,
            'story_static' => false,
        );

        $stores = $story_handler->News_StoryAdminList($story_infos);
        $story_numrows = $story_handler->News_StoryAdminCount($story_infos);

        if ($story_numrows > $story_limit) {
            if ($story_topic) {
                $story_pagenav = new XoopsPageNav($story_numrows, $story_limit, $story_start, 'start', 'limit=' . $story_limit . '&topic=' . $story_topic);
            } else {
                $story_pagenav = new XoopsPageNav($story_numrows, $story_limit, $story_start, 'start', 'limit=' . $story_limit);
            }
            $story_pagenav = $story_pagenav->renderNav(4);
        } else {
            $story_pagenav = '';
        }

        $xoopsTpl->assign('navigation', 'content');
        $xoopsTpl->assign('navtitle', _NEWS_MI_ARTICLE);
        $xoopsTpl->assign('topic_title', $topic_title);
        $xoopsTpl->assign('contents', $stores);
        $xoopsTpl->assign('story_pagenav', $story_pagenav);
        $xoopsTpl->assign('xoops_dirname', 'news');

        // Call template file
        $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/news/templates/admin/news_article.html');

        break;

}

// Admin Footer
include "footer.php";
xoops_cp_footer();

?>