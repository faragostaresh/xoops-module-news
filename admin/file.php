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
// Define scripts
$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
$xoTheme->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
$xoTheme->addScript(XOOPS_URL . '/modules/news/js/order.js');
$xoTheme->addScript(XOOPS_URL . '/modules/news/js/admin.js');
// Add module stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/news/css/admin.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/ui/' . xoops_getModuleOption('jquery_theme', 'system') . '/ui.all.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');

switch ($op) {
    case 'new_file':
        $obj = $file_handler->create();
        $obj->getForm();
        break;

    case 'edit_file':
        $file_id = NewsUtils::News_UtilityCleanVars($_REQUEST, 'file_id', 0, 'int');
        if ($file_id > 0) {
            $obj = $file_handler->get($file_id);
            $obj->getForm();
        } else {
            NewsUtils::News_UtilityRedirect('file.php', 1, _NEWS_AM_MSG_EDIT_ERROR);
        }
        break;

    case 'delete_file':
        $file_id = NewsUtils::News_UtilityCleanVars($_REQUEST, 'file_id', 0, 'int');
        if ($file_id > 0) {
            $file = $file_handler->get($file_id);
            // Prompt message
            NewsUtils::News_UtilityMessage('backend.php', sprintf(_NEWS_AM_MSG_DELETE, '"' . $file->getVar('file_title') . '"'), $file_id, 'file');
            // Display Admin footer
            xoops_cp_footer();
        }

    default:
        $file = array();
        // get module configs

        $file['perpage'] = '10';
        $file['order'] = 'DESC';
        $file['sort'] = 'file_id';

        // get limited information
        if (isset($_REQUEST['limit'])) {
            $file['limit'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'limit', 0, 'int');
        } else {
            $file['limit'] = $file['perpage'];
        }

        // get start information
        if (isset($_REQUEST['start'])) {
            $file['start'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'start', 0, 'int');
        } else {
            $file['start'] = 0;
        }

        // get content
        if (isset($_REQUEST['content'])) {
            $file['content'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'content', 0, 'int');
            $story = $story_handler->get($file['content']);
        } else {
            $story = $story_handler->getall();
        }


        $files = $file_handler->News_FileAdminList($file, $story);

        $file_numrows = $file_handler->News_FileCount();

        if ($file_numrows > $file['limit']) {
            $file_pagenav = new XoopsPageNav($file_numrows, $file['limit'], $file['start'], 'start', 'limit=' . $file['limit']);
            $file_pagenav = $file_pagenav->renderNav(4);
        } else {
            $file_pagenav = '';
        }

        $xoopsTpl->assign('navigation', 'file');
        $xoopsTpl->assign('navtitle', _NEWS_MI_FILE);
        $xoopsTpl->assign('files', $files);
        $xoopsTpl->assign('file_pagenav', $file_pagenav);
        $xoopsTpl->assign('xoops_dirname', 'news');

        // Call template file
        $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/news/templates/admin/news_file.html');

        break;
}

// Display Xoops footer
include "footer.php";
xoops_cp_footer();

?> 
