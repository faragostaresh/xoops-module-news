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


$index_admin = new ModuleAdmin();
// Display Admin header
xoops_cp_header();
// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/news/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

$folder = array(
	XOOPS_ROOT_PATH . '/uploads/news/', 
	XOOPS_ROOT_PATH . xoops_getModuleOption ( 'img_dir', 'news' ),
	XOOPS_ROOT_PATH . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/',
	XOOPS_ROOT_PATH . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/',
	XOOPS_ROOT_PATH . xoops_getModuleOption ( 'img_dir', 'news' ) . '/original/',
	XOOPS_ROOT_PATH . xoops_getModuleOption ( 'file_dir', 'news' )
);

$story_infos = array(
   'story_topic' => null,
);
        
$index_admin = new ModuleAdmin();
$index_admin->addInfoBox(_NEWS_AM_INDEX_ADMENU1);
$index_admin->addInfoBox(_NEWS_AM_INDEX_ADMENU2);
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU1, _NEWS_AM_INDEX_TOPICS, $topic_handler->News_TopicCount());
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU2, _NEWS_AM_INDEX_CONTENTS, $story_handler->News_StoryAllCount());
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU2, _NEWS_AM_INDEX_CONTENTS_OFFLINE, $story_handler->News_StoryOfflineCount($story_infos));
$index_admin->addInfoBoxLine(_NEWS_AM_INDEX_ADMENU2, _NEWS_AM_INDEX_CONTENTS_EXPIRE, $story_handler->News_StoryExpireCount($story_infos));

foreach (array_keys( $folder) as $i) {
    $index_admin->addConfigBoxLine($folder[$i], 'folder');
    $index_admin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}
    
$xoopsTpl->assign('navigation', 'index');
$xoopsTpl->assign('navtitle', _NEWS_MI_HOME);
$xoopsTpl->assign('renderindex', $index_admin->renderIndex());

// Call template file
$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/news/templates/admin/news_index.html');

// Display Xoops footer
include "footer.php";
xoops_cp_footer();
?>