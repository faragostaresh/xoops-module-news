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
require dirname(__FILE__) . '/header.php';
// Include content template
$xoopsOption ['template_main'] = 'news_article.html';
// include Xoops header
include XOOPS_ROOT_PATH . '/header.php';
// Add Stylesheet
$xoTheme->addStylesheet ( XOOPS_URL . '/modules/news/css/style.css' );

// get story id
if(isset($_REQUEST['storyid'])) {
	$story_id = NewsUtils::News_UtilityCleanVars ( $_REQUEST, 'storyid', 0, 'int' );
} else {
	$story_alias = NewsUtils::News_UtilityCleanVars ( $_REQUEST, 'story', 0, 'string' );
	if($story_alias) {
		$_GET['storyid'] = $story_id = $story_handler->News_StoryGetId($story_alias);
	}
}

if (empty($story_id)) {
  redirect_header ( 'index.php', 3, _NEWS_MD_ERROR_EXIST);
  exit ();
}

$story = array();
$obj = $story_handler->get ( $story_id );

if(!$obj) {
  redirect_header ( 'index.php', 3, _NEWS_MD_ERROR_EXIST);
  exit ();
}

$story_topic = $obj->getVar ( 'story_topic' );

if(!$obj->getVar ( 'story_status' )) {
	redirect_header ( 'index.php', 3, _NEWS_MD_ERROR_STATUS );
	exit ();
}	
	
$story = $obj->toArray ();

// Update content hits
$story_handler->News_StoryUpdateHits ( $story_id );

// set arrey
$view_topic = $topic_handler->get ( $story_topic );
$story ['topic'] = $view_topic->getVar ( 'topic_title' );
$story ['topic_alias'] = $view_topic->getVar ( 'topic_alias' );
$story ['topic_id'] = $view_topic->getVar ( 'topic_id' );
$story ['story_publish'] = formatTimestamp ( $story ['story_publish'], _MEDIUMDATESTRING );
$story ['story_update'] = formatTimestamp ( $story ['story_update'], _MEDIUMDATESTRING );
$story ['imageurl'] = XOOPS_URL . '/uploads/news/image/medium/' . $story ['story_img'];
$story ['thumburl'] = XOOPS_URL . '/uploads/news/image/thumb/' . $story ['story_img'];

if (isset ( $story_topic ) && $story_topic > 0) {
	
	if (! isset ( $view_topic )) {
		redirect_header ( 'index.php', 3, _NEWS_MD_TOPIC_ERROR );
		exit ();
	}
	
	if ($view_topic->getVar ( 'topic_online' ) == '0') {
		redirect_header ( 'index.php', 3, _NEWS_MD_TOPIC_ERROR );
		exit ();
	}
	
	// Check the access permission
	if (! $perm_handler->News_PermissionIsAllowed ( $xoopsUser, 'news_view', $view_topic->getVar ( 'topic_id' ) )) {
		redirect_header ( "index.php", 3, _NOPERM );
		exit ();
	}
}

$link = array ();

if (isset ( $story_topic ) && $story_topic > 0 && $view_topic->getVar ( 'topic_showtype' ) != '0') { // The option for select setting from topic or module options must be added
	

	// get topic confing from topic
	if ($view_topic->getVar ( 'topic_showtopic' )) {
		$link ['topic'] = $view_topic->getVar ( 'topic_title' );
		$link ['topicid'] = $story_topic;
		$link ['topicshow'] = '1';
	}
	if ($view_topic->getVar ( 'topic_showauthor' )) {
		$story ['author'] = XoopsUser::getUnameFromId ( $obj->getVar ( 'story_uid' ) );
	}
	if ($view_topic->getVar ( 'topic_showdate' )) {
		$link ['date'] = '1';
	}
	if ($view_topic->getVar ( 'topic_showpdf' )) {
		$link ['pdf'] = NewsUtils::News_UtilityStoryUrl ( $story, 'pdf' );
	}
	if ($view_topic->getVar ( 'topic_showprint' )) {
		$link ['print'] = NewsUtils::News_UtilityStoryUrl ( $story, 'print' );
	}
	if ($view_topic->getVar ( 'topic_showhits' )) {
		$link ['hits'] = '1';
	}
	if ($view_topic->getVar ( 'topic_showcoms' ) == '1') {
		$link ['coms'] = '1';
	}
	if ($view_topic->getVar ( 'topic_showmail' )) {
		// Mail link & label
		$link ['mail_subject'] = $story ['story_title'] . ' - ' . $xoopsConfig ['sitename'];
		$link ['mail_linkto'] = NewsUtils::News_UtilityStoryUrl ( $story );
		if (xoops_getModuleOption ( 'tellafriend')) {
			$link ['mail'] = "mailto:|xoops_tellafriend:" . $link ['mail_subject'];
		} else {
			$link ['mail'] = "mailto:?subject=" . $link ['mail_subject'] . "&amp;body=" . $link ['mail_linkto'];
		}
	}
	if ($view_topic->getVar ( 'topic_shownav' )) {
		if ($obj->getVar ( 'story_next' ) != 0) {
			$next_obj = $story_handler->get ( $obj->getVar ( 'story_next' ) );
			$next_link = $next_obj->toArray ();
			$next_link ['topic'] = $story ['topic'];
			$link ['next'] = NewsUtils::News_UtilityStoryUrl ( $next_link );
			$link ['next_title'] = $next_link ['story_title'];
		}
		if ($obj->getVar ( 'story_prev' ) != 0) {
			$prev_obj = $story_handler->get ( $obj->getVar ( 'story_prev' ) );
			$prev_link = $prev_obj->toArray ();
			$prev_link ['topic'] = $story ['topic'];
			$link ['prev'] = NewsUtils::News_UtilityStoryUrl ( $prev_link );
			$link ['prev_title'] = $prev_link ['story_title'];
		}
	}

} else {
	
	// get topic config from module option
	if (xoops_getModuleOption ( 'disp_topic')) {
		$link ['topic'] = $view_topic->getVar ( 'topic_title' );
		$link ['topicid'] = $story_topic;
		if ($story_topic) {
			$link ['topicshow'] = '1';
		} else {
			$link ['topicshow'] = '0';
		}
	}
	if (xoops_getModuleOption ( 'disp_date', 'news' )) {
		$link ['date'] = XoopsUser::getUnameFromId ( $obj->getVar ( 'story_publish' ) );
	}
	if (xoops_getModuleOption ( 'disp_author', 'news' )) {
		$story ['author'] = XoopsUser::getUnameFromId ( $obj->getVar ( 'story_uid' ) );
	}
	if (xoops_getModuleOption ( 'disp_pdflink', 'news' )) {
		$link ['pdf'] = NewsUtils::News_UtilityStoryUrl ( $story, 'pdf' );
	}
	if (xoops_getModuleOption ( 'disp_printlink', 'news' )) {
		$link ['print'] = NewsUtils::News_UtilityStoryUrl ($story, 'print' );
	}
	if (xoops_getModuleOption ( 'disp_hits', 'news' )) {
		$link ['hits'] = '1';
	}
	if (xoops_getModuleOption ( 'disp_coms', 'news' )) {
		$link ['coms'] = '1';
	}
	if (xoops_getModuleOption ( 'disp_maillink', 'news' )) {
		// Mail link & label
		$link ['mail_subject'] = $story ['story_title'] . ' - ' . $xoopsConfig ['sitename'];
		$link ['mail_linkto'] = NewsUtils::News_UtilityStoryUrl ($story );
		if (xoops_getModuleOption ( 'tellafriend', 'news' )) {
			$link ['mail'] = "mailto:|xoops_tellafriend:" . $link ['mail_subject'];
		} else {
			$link ['mail'] = "mailto:?subject=" . $link ['mail_subject'] . "&amp;body=" . $link ['mail_linkto'];
		}
	}
	if (xoops_getModuleOption ( 'disp_navlink', 'news' )) {
		if ($obj->getVar ( 'story_next' ) != 0) {
			$next_obj = $story_handler->get ( $obj->getVar ( 'story_next' ) );
			$next_link = $next_obj->toArray ();
			$next_link ['topic'] = $story ['topic'];
			$link ['next'] = NewsUtils::News_UtilityStoryUrl ( $next_link );
			$link ['next_title'] = $next_link ['story_title'];
		}
		if ($obj->getVar ( 'story_prev' ) != 0) {
			$prev_obj = $story_handler->get ( $obj->getVar ( 'story_prev' ) );
			$prev_link = $prev_obj->toArray ();
			$prev_link ['topic'] = $story ['topic'];
			$link ['prev'] = NewsUtils::News_UtilityStoryUrl ( $prev_link );
			$link ['prev_title'] = $prev_link ['story_title'];
		}
	}
}

if (xoops_getModuleOption ( 'img_lightbox', 'news' )) {
	// Add scripts
	$xoTheme->addScript ( 'browse.php?Frameworks/jquery/jquery.js' );
	$xoTheme->addScript ( 'browse.php?Frameworks/jquery/plugins/jquery.lightbox.js' );
	// Add Stylesheet
	$xoTheme->addStylesheet ( XOOPS_URL . '/modules/system/css/lightbox.css' );
	$xoopsTpl->assign ( 'img_lightbox', true );
}

if (file_exists ( XOOPS_ROOT_PATH . '/modules/news/language/' . $GLOBALS ['xoopsConfig'] ['language'] . '/main.php' )) {
	$xoopsTpl->assign ( 'xoops_language', $GLOBALS ['xoopsConfig'] ['language'] );
} else {
	$xoopsTpl->assign ( 'xoops_language', 'english' );
}

if (isset ( $xoTheme ) && is_object ( $xoTheme )) {
	if ($story ['story_words'] != '') {
		$xoTheme->addMeta ( 'meta', 'keywords', $story ['story_words'] );
	}
	if ($story ['story_desc'] != '') {
		$xoTheme->addMeta ( 'meta', 'description', $story ['story_desc'] );
	}
} elseif (isset ( $xoopsTpl ) && is_object ( $xoopsTpl )) { // Compatibility for old Xoops versions
	if ($story ['story_words'] != '') {
		$xoopsTpl->assign ( 'xoops_meta_keywords', $story ['story_words'] );
	}
	if ($story ['story_desc'] != '') {
		$xoopsTpl->assign ( 'xoops_meta_description', $story ['story_desc'] );
	}
}

// For social networks scripts
if (xoops_getModuleOption ( 'show_social_book', 'news' ) == '1' || xoops_getModuleOption ( 'show_social_book', 'news' ) == '3') {
	$xoTheme->addScript ( 'http://platform.twitter.com/widgets.js' );
	$xoTheme->addScript ( 'http://connect.facebook.net/en_US/all.js#xfbml=1' );
	$xoTheme->addScript ( 'https://apis.google.com/js/plusone.js' );
}

// For xoops tag
if ((xoops_getModuleOption ( 'usetag', 'news' )) and (is_dir ( XOOPS_ROOT_PATH . '/modules/tag' ))) {
	include_once XOOPS_ROOT_PATH . "/modules/tag/include/tagbar.php";
	$xoopsTpl->assign ( 'tagbar', tagBar ( $story ['story_id'], $catid = 0 ) );
	$xoopsTpl->assign ( 'tags', true );
} else {
	$xoopsTpl->assign ( 'tags', false );
}

// Get URLs 
$link ['url'] = NewsUtils::News_UtilityStoryUrl ( $story );
$link ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( $story );

// breadcrumb
if (xoops_getModuleOption ( 'bc_show', 'news' )) {
	$breadcrumb = NewsUtils::News_UtilityBreadcrumb ( true, $story ['story_title'], $story ['story_topic'], ' &raquo; ', 'topic_title' );
}


// Get Attached files information
if($story ['story_file'] > 0) {
	$file = array();
	$file['order'] = 'DESC';
   $file['sort'] = 'file_id';
	$file['start'] = 0;
	$file['content'] = $story_id;
	$view_file = $file_handler->News_FileList($file);
	$xoopsTpl->assign ( 'files', $view_file );
	$xoopsTpl->assign ( 'jwwidth', '470' );
	$xoopsTpl->assign ( 'jwheight', '320' );
}	

// Get related contents
if(xoops_getModuleOption ( 'related', 'news' )) {
	$related_infos ['story_id'] = $obj->getVar ( 'story_id' );
	$related_infos ['story_topic'] = $obj->getVar ( 'story_topic' );
	$related_infos ['story_limit'] = xoops_getModuleOption ( 'related_limit', 'news' );
	$related_infos ['topic_alias'] = $view_topic->getVar ( 'topic_alias' );
	$related = $story_handler->News_StoryRelated($related_infos);
	$xoopsTpl->assign ( 'related', $related );
}	
 
// Add topic style if set
if(file_exists(XOOPS_ROOT_PATH .'/modules/news/css/' . $view_topic->getVar ( 'topic_style' ) . '.css')) {
	$xoTheme->addStylesheet ( XOOPS_URL . '/modules/news/css/' . $view_topic->getVar ( 'topic_style' ) . '.css');
}
 
// Vote system
if(xoops_getModuleOption('vote_active', 'news')) {
   // Add scripts
	$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
	$xoTheme->addScript(XOOPS_URL . '/modules/news/js/rateit.js');
	// Add Stylesheet
	$xoTheme->addStylesheet (XOOPS_URL . '/modules/news/css/rateit.css');
	$xoopsTpl->assign('vote', true);
}

$xoopsTpl->assign ( 'content', $story );
$xoopsTpl->assign ( 'link', $link );
$xoopsTpl->assign ( 'xoops_pagetitle', $story ['story_title'] );
$xoopsTpl->assign ( 'rss', xoops_getModuleOption ( 'rss_show', 'news' ) );
$xoopsTpl->assign ( 'multiple_columns', xoops_getModuleOption ( 'multiple_columns', 'news' ) );
$xoopsTpl->assign ( 'show_social_book', xoops_getModuleOption ( 'show_social_book', 'news' ) );
$xoopsTpl->assign ( 'advertisement', xoops_getModuleOption ( 'advertisement', 'news' ) );
$xoopsTpl->assign ( 'imgwidth', xoops_getModuleOption ( 'imgwidth', 'news' ) );
$xoopsTpl->assign ( 'imgfloat', xoops_getModuleOption ( 'imgfloat', 'news' ) );
$xoopsTpl->assign ( 'alluserpost', xoops_getModuleOption ( 'alluserpost', 'news' ) );
$xoopsTpl->assign ( 'breadcrumb', $breadcrumb );

// include Xoops footer
include XOOPS_ROOT_PATH . '/include/comment_view.php';
include XOOPS_ROOT_PATH . '/footer.php';

?>