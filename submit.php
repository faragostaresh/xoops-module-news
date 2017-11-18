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
 * News submit file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

// Include module header
require dirname(__FILE__) . '/header.php';
// Include content template
$xoopsOption ['template_main'] = 'news_submit.tpl';
// include Xoops header
require_once XOOPS_ROOT_PATH . '/header.php';
// Add Stylesheet
$xoTheme->addStylesheet(XOOPS_URL . '/modules/news/css/style.css');

// Include language file
xoops_loadLanguage('admin', 'news');

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . "/class/tree.php";

$op = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'op', '', 'string');

// Check the access permission
global $xoopsUser;
if (!$perm_handler->NewsPermissionIsAllowed($xoopsUser, 'news_ac', '8')) {
    redirect_header("index.php", 3, _NOPERM);
    exit ();
}

switch ($op) {
    case 'add' :

        if (!isset ($_POST ['post'])) {
            redirect_header("index.php", 3, _NOPERM);
            exit ();
        }

        $groups = xoops_getModuleOption('groups', 'news');
        $groups = (isset ($groups)) ? $groups : '';
        $groups = (is_array($groups)) ? implode(" ", $groups) : '';

        $obj = $story_handler->create();
        $obj->setVars($_REQUEST);

        $obj->setVar('story_alias', NewsUtils::NewsUtilityAliasFilter($_REQUEST ['story_title']));
        $obj->setVar('story_words', NewsUtils::NewsUtilityMetaFilter($_REQUEST ['story_title']));
        $obj->setVar('story_desc', NewsUtils::NewsUtilityAjaxFilter($_REQUEST ['story_title']));
        $obj->setVar('story_create', time());
        $obj->setVar('story_update', time());
        $obj->setVar('story_publish', time());

        //Form topic_img
        NewsUtils::NewsUtilityUploadImg('story_img', $obj, $_REQUEST ['story_img']);

        if ($perm_handler->NewsPermissionIsAllowed($xoopsUser, 'news_ac', '16')) {
            $obj->setVar('story_status', '1');
            $story_handler->NewsStoryUpdatePost($_REQUEST ['story_uid'], '1', $story_action = 'add');
        }

        if (!$story_handler->insert($obj)) {
            NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_MD_MSG_ERROR);
            require_once XOOPS_ROOT_PATH . '/footer.php';
            exit ();
        }

        // Reset next content for previous content
        $story_handler->NewsResetNext($_REQUEST ['story_topic'], $obj->getVar('story_id'));
        $story_handler->NewsResetPrevious($_REQUEST ['story_topic'], $obj->getVar('story_id'));

        if ((xoops_getModuleOption('usetag', 'news')) and (is_dir(XOOPS_ROOT_PATH . '/modules/tag'))) {
            $tag_handler = xoops_getmodulehandler('tag', 'tag');
            $tag_handler->updateByItem($_POST ["item_tag"], $obj->getVar('story_id'), 'news', 0);
        }

        // file
        if (isset($_FILES['file_name']['name']) && !empty($_FILES['file_name']['name'])) {
            $fileobj = $file_handler->create();
            $fileobj->setVar('file_date', time());
            $fileobj->setVar('file_title', $_REQUEST ['story_title']);
            $fileobj->setVar('file_story', $obj->getVar('story_id'));
            $fileobj->setVar('file_status', 1);

            NewsUtils::NewsUtilityUploadFile('file_name', $fileobj, $_REQUEST ['file_name']);
            $story_handler->NewsStoryFile('add', $obj->getVar('story_id'));
            if (!$file_handler->insert($fileobj)) {
                NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_MD_MSG_ERROR);
                xoops_cp_footer();
                exit ();
            }
        }

        // Redirect page
        NewsUtils::NewsUtilityRedirect('index.php', 1, _NEWS_MD_MSG_WAIT);
        require_once XOOPS_ROOT_PATH . '/footer.php';
        exit ();
        break;

    default :
        // Form
        $story_type = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'story_type', 'news', 'string');
        $obj = $story_handler->create();
        $form = $obj->NewsStorySimpleForm($story_type);
        $xoopsTpl->assign('form', $form->render());
        // breadcrumb
        if (xoops_getModuleOption('bc_show', 'news')) {
            $breadcrumb = NewsUtils::NewsUtilityBreadcrumb('submit.php', _NEWS_MD_SUBMIT, 0, ' &raquo; ');
            $xoopsTpl->assign('breadcrumb', $breadcrumb);
        }
        break;

}

// include Xoops footer
require_once XOOPS_ROOT_PATH . '/footer.php';

?>