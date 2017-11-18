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

// Define default value
$op = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'op', 'new', 'string');
// Admin header
xoops_cp_header();
// Redirect to content page
if (!isset ($op)) {
    NewsUtils::NewsUtilityRedirect('index.php', 0, _NEWS_AM_MSG_WAIT);
    // Include footer
    xoops_cp_footer();
    exit ();
}

switch ($op) {

    case 'add_topic' :
        $obj = $topic_handler->create();
        $obj->setVars($_REQUEST);

        if ($topic_handler->NewsTopicExistAlias($_REQUEST)) {
            NewsUtils::NewsUtilityRedirect("javascript:history.go(-1)", 3, _NEWS_AM_MSG_ALIASERROR);
            xoops_cp_footer();
            exit ();
        }

        $obj->setVar('topic_date_created', time());
        $obj->setVar('topic_date_update', time());
        $obj->setVar('topic_weight', $topic_handler->NewsTopicOrder());

        //image
        if (isset($_REQUEST['topic_img']) && !empty($_REQUEST['topic_img'])) {
            NewsUtils::NewsUtilityUploadImg('topic_img', $obj, $_REQUEST['topic_img']);
        }

        if (!$topic_handler->insert($obj)) {
            NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
            xoops_cp_footer();
            exit ();
        }

        $topic_id = $obj->db->getInsertId();

        //permission
        NewsPermission::NewsPermissionSet('news_view', $_POST ['groups_view'], $topic_id, true);
        NewsPermission::NewsPermissionSet('news_submit', $_POST ['groups_submit'], $topic_id, true);

        // Redirect page
        NewsUtils::NewsUtilityRedirect('topic.php', 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'edit_topic' :
        $topic_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'topic_id', 0, 'int');
        if ($topic_id > 0) {

            $obj = $topic_handler->get($topic_id);
            $obj->setVars($_POST);
            $obj->setVar('topic_date_update', time());

            if ($topic_handler->NewsTopicExistAlias($_REQUEST)) {
                NewsUtils::NewsUtilityRedirect("javascript:history.go(-1)", 3, _NEWS_AM_MSG_ALIASERROR);
                xoops_cp_footer();
                exit ();
            }

            //image
            NewsUtils::NewsUtilityUploadImg('topic_img', $obj, $_REQUEST ['topic_img']);
            if (isset ($_POST ['deleteimage']) && intval($_POST ['deleteimage']) == 1) {
                NewsUtils::NewsUtilityDeleteImg('topic_img', $obj);
            }
            //permission
            NewsPermission::NewsPermissionSet('news_view', $_POST ['groups_view'], $topic_id, false);
            NewsPermission::NewsPermissionSet('news_submit', $_POST ['groups_submit'], $topic_id, false);

            if (!$topic_handler->insert($obj)) {
                NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
                xoops_cp_footer();
                exit ();
            }
        }

        // Redirect page
        NewsUtils::NewsUtilityRedirect('topic.php', 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'add' :
        $obj = $story_handler->create();
        $obj->setVars($_REQUEST);

        if ($story_handler->NewsStoryExistAlias($_REQUEST)) {
            NewsUtils::NewsUtilityRedirect("javascript:history.go(-1)", 3, _NEWS_AM_MSG_ALIASERROR);
            xoops_cp_footer();
            exit ();
        }

        if (!$_REQUEST ['story_default'] && $_REQUEST ['story_topic'] == 0) {
            $criteria = new CriteriaCompo ();
            $criteria->add(new Criteria ('story_topic', 0));
            $criteria->add(new Criteria ('story_default', 1));
            if (!$story_handler->getCount($criteria)) {
                $obj->setVar('story_default', '1');
            }
        }
        $obj->setVar('story_create', time());
        $obj->setVar('story_update', time());

        // Set publish and expire
        if ($_REQUEST ['autopublish'] && $_REQUEST ['story_publish']) {
            $obj->setVar('story_publish', strtotime($_REQUEST ['story_publish']['date']) + $_REQUEST ['story_publish']['time']);
        } else {
            $obj->setVar('story_publish', time());
        }

        if ($_REQUEST ['autoexpire'] && $_REQUEST ['story_expire']) {
            $obj->setVar('story_expire', strtotime($_REQUEST ['story_expire']['date']) + $_REQUEST ['story_expire']['time']);
        } else {
            $obj->setVar('story_expire', 0);
        }

        //image
        NewsUtils::NewsUtilityUploadImg('story_img', $obj, $_REQUEST ['story_img']);

        $story_handler->NewsStoryUpdatePost($_REQUEST ['story_uid'], $_REQUEST ['story_status'], $story_action = 'add');


        if (!$story_handler->insert($obj)) {
            NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
            xoops_cp_footer();
            exit ();
        }

        // Reset next and previous content
        $story_handler->NewsResetNext($_REQUEST ['story_topic'], $obj->getVar('story_id'));
        $story_handler->NewsResetPrevious($_REQUEST ['story_topic'], $obj->getVar('story_id'));

        // tag
        if ((xoops_getModuleOption('usetag', 'news')) and (is_dir(XOOPS_ROOT_PATH . '/modules/tag'))) {
            $tag_handler = xoops_getmodulehandler('tag', 'tag');
            $tag_handler->updateByItem($_POST ["item_tag"], $obj->getVar('story_id'), 0);
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
                NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
                xoops_cp_footer();
                exit ();
            }
        }

        // Redirect page
        NewsUtils::NewsUtilityRedirect('article.php', 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'edit' :
        $story_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'story_id', 0, 'int');
        if ($story_id > 0) {

            $obj = $story_handler->get($story_id);
            $obj->setVars($_REQUEST);
            $obj->setVar('story_update', time());

            // Set publish and expire
            if ($_REQUEST ['autopublish'] && $_REQUEST ['story_publish']) {
                $obj->setVar('story_publish', strtotime($_REQUEST ['story_publish']['date']) + $_REQUEST ['story_publish']['time']);
            } else {
                $obj->setVar('story_publish', $obj->getVar('story_create'));
            }

            if ($_REQUEST ['autoexpire'] && $_REQUEST ['story_expire']) {
                $obj->setVar('story_expire', strtotime($_REQUEST ['story_expire']['date']) + $_REQUEST ['story_expire']['time']);
            } else {
                $obj->setVar('story_expire', 0);
            }

            if ($story_handler->NewsStoryExistAlias($_REQUEST)) {
                NewsUtils::NewsUtilityRedirect("javascript:history.go(-1)", 3, _NEWS_AM_MSG_ALIASERROR);
                xoops_cp_footer();
                exit ();
            }

            if (!isset ($_REQUEST ['dohtml'])) {
                $obj->setVar('dohtml', 0);
            }

            if (!isset ($_REQUEST ['dobr'])) {
                $obj->setVar('dobr', 0);
            }

            if (!isset ($_REQUEST ['doimage'])) {
                $obj->setVar('doimage', 0);
            }

            if (!isset ($_REQUEST ['dosmiley'])) {
                $obj->setVar('dosmiley', 0);
            }

            if (!isset ($_REQUEST ['doxcode'])) {
                $obj->setVar('doxcode', 0);
            }

            //image
            NewsUtils::NewsUtilityUploadImg('story_img', $obj, $_REQUEST ['story_img']);
            if (isset ($_POST ['deleteimage']) && intval($_POST ['deleteimage']) == 1) {
                NewsUtils::NewsUtilityDeleteImg('story_img', $obj);
            }

            if (!$story_handler->insert($obj)) {
                NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
                xoops_cp_footer();
                exit ();
            }

            //tag
            if ((xoops_getModuleOption('usetag', 'news')) and (is_dir(XOOPS_ROOT_PATH . '/modules/tag'))) {
                $tag_handler = xoops_getmodulehandler('tag', 'tag');
                $tag_handler->updateByItem($_POST ["item_tag"], $story_id, $catid = 0);
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
                    NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
                    xoops_cp_footer();
                    exit ();
                }
            }

        }

        // Redirect page
        NewsUtils::NewsUtilityRedirect('article.php', 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'add_file' :

        $obj = $file_handler->create();
        $obj->setVars($_REQUEST);
        $obj->setVar('file_date', time());

        NewsUtils::NewsUtilityUploadFile('file_name', $obj, $_REQUEST ['file_name']);
        $story_handler->NewsStoryFile('add', $_REQUEST['file_story']);
        if (!$file_handler->insert($obj)) {
            NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
            xoops_cp_footer();
            exit ();
        }

        // Redirect page
        NewsUtils::NewsUtilityRedirect('file.php', 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'edit_file' :
        $file_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'file_id', 0, 'int');
        if ($file_id > 0) {

            $obj = $file_handler->get($file_id);
            $obj->setVars($_REQUEST);

            if ($_REQUEST['file_story'] != $_REQUEST['file_previous']) {
                $story_handler->NewsStoryFile('add', $_REQUEST['file_story']);
                $story_handler->NewsStoryFile('delete', $_REQUEST['file_previous']);
            }

            if (!$file_handler->insert($obj)) {
                NewsUtils::NewsUtilityRedirect('onclick="javascript:history.go(-1);"', 1, _NEWS_AM_MSG_ERROR);
                xoops_cp_footer();
                exit ();
            }
        }
        // Redirect page
        NewsUtils::NewsUtilityRedirect('file.php', 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'status' :
        $story_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'story_id', 0, 'int');
        if ($story_id > 0) {
            $obj = $story_handler->get($story_id);
            $old = $obj->getVar('story_status');
            $story_handler->NewsStoryUpdatePost($obj->getVar('story_uid'), $obj->getVar('story_status'), $story_action = 'status');
            $obj->setVar('story_status', !$old);
            $obj->setVar('story_publish', time());
            if ($story_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'default' :
        $story_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'story_id', 0, 'int');
        $topic_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'topic_id', 0, 'int');
        if ($story_id > 0) {
            $criteria = new CriteriaCompo ();
            $criteria->add(new Criteria ('story_topic', $topic_id));
            $story_handler->updateAll('story_default', 0, $criteria);
            $obj = $story_handler->get($story_id);
            $obj->setVar('story_default', 1);
            if ($story_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'important' :
        $story_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'story_id', 0, 'int');
        if ($story_id > 0) {
            $obj = $story_handler->get($story_id);
            $old = $obj->getVar('story_important');
            $obj->setVar('story_important', !$old);
            if ($story_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'topic_asmenu' :
        $topic_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'topic_id', 0, 'int');
        if ($topic_id > 0) {
            $obj = $topic_handler->get($topic_id);
            $old = $obj->getVar('topic_asmenu');
            $obj->setVar('topic_asmenu', !$old);
            if ($topic_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'topic_online' :
        $topic_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'topic_id', 0, 'int');
        if ($topic_id > 0) {
            $obj = $topic_handler->get($topic_id);
            $old = $obj->getVar('topic_online');
            $obj->setVar('topic_online', !$old);
            if ($topic_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'topic_show' :
        $topic_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'topic_id', 0, 'int');
        if ($topic_id > 0) {
            $obj = $topic_handler->get($topic_id);
            $old = $obj->getVar('topic_show');
            $obj->setVar('topic_show', !$old);
            if ($topic_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'file_status' :
        $file_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'file_id', 0, 'int');
        if ($file_id > 0) {
            $obj = $file_handler->get($file_id);
            $old = $obj->getVar('file_status');
            $obj->setVar('file_status', !$old);
            if ($file_handler->insert($obj)) {
                exit ();
            }
            echo $obj->getHtmlErrors();
        }
        break;

    case 'delete' :
        //print_r($_POST);
        $id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'id', 0, 'int');
        $handler = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'handler', 0, 'string');
        if ($id > 0 && $handler) {
            switch ($handler) {
                case 'content':
                    $obj = $story_handler->get($id);
                    $url = 'article.php';
                    $story_handler->NewsStoryUpdatePost($obj->getVar('story_uid'), $obj->getVar('story_status'), $story_action = 'delete');
                    if (!$story_handler->delete($obj)) {
                        echo $obj->getHtmlErrors();
                    }
                    break;
                case 'topic':
                    $obj = $topic_handler->get($id);
                    $url = 'topic.php';
                    if (!$topic_handler->delete($obj)) {
                        echo $obj->getHtmlErrors();
                    }
                    break;
                case 'file':
                    $obj = $file_handler->get($id);
                    $url = 'file.php';
                    $story_handler->NewsStoryFile('delete', $obj->getVar('file_story'));
                    if (!$file_handler->delete($obj)) {
                        echo $obj->getHtmlErrors();
                    }
                    break;
            }
        }

        // Redirect page
        NewsUtils::NewsUtilityRedirect($url, 1, _NEWS_AM_MSG_WAIT);
        xoops_cp_footer();
        exit ();
        break;

    case 'push':
        $story_id = NewsUtils::NewsUtilityCleanVars($_REQUEST, 'story_id', 0, 'int');
        if ($story_id > 0) {
            $obj = $story_handler->get($story_id);
            $story = $obj->toArray();


            $notification = [
                'id'    => $story['story_id'],
                'title' => $story['story_title'],
                'body'  => strip_tags($story['story_short']),
            ];

            $target = '/topics/ain';

            //FCM api URL
            $url = 'https://fcm.googleapis.com/fcm/send';
            //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key = '';

            $fields = [];
            $fields['notification'] = $notification;
            $fields['to'] = '/topics/ain';
            $fields['priority'] = 'high';
            $fields['sound'] = 'default';
            $headers = [
                'Content-Type:application/json',
                'Authorization:key=' . $server_key,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);

            exit ();
        }
        break;
}

// Redirect page
NewsUtils::NewsUtilityRedirect('index.php', 1, _NEWS_AM_MSG_WAIT);
// Include footer
xoops_cp_footer();

?>