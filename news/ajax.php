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

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

// Set option
$op = NewsUtils::News_UtilityCleanVars($_REQUEST, 'op', '', 'string');

if (!empty($op)) {
    switch ($op) {
        // Get last story as json
        case 'liststory':
        case 'story':
            $story_infos = array();
            $story_infos['topics'] = $topic_handler->getall();
            $story_infos['story_start'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'start', 0, 'int');
            $story_infos['story_topic'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'storytopic', 0, 'int');
            $story_infos['story_limit'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'limit', 10, 'int');
            $story_infos['story_type'] = NewsUtils::News_UtilityCleanVars($_REQUEST, 'storytype', '', 'string');
            $return = $story_handler->News_StoryJson($story_infos);
            break;

        // Get single story as json
        case 'singlestory':
            $ret = array();
            $story_id = NewsUtils::News_UtilityCleanVars($_REQUEST, 'storyid', 0, 'int');
            $obj = $story_handler->get($story_id);
            $story = $obj->toArray();

            $json['story_id'] = $story['story_id'];
            $json['story_alias'] = $story['story_alias'];
            $json['story_publish'] = $story['story_publish'];
            $json['story_topic'] = $story['story_topic'];
            $json['story_img'] = $story['story_img'];
            $json['story_hits'] = $story['story_hits'];

            if ($story['story_topic'] > 0) {
                $topicObj = $topic_handler->get($story['story_topic']);
                $json['story_topic_title'] = $topicObj->getVar('topic_title');
            } else {
                $json['story_topic_title'] = '';
            }

            $text = $story['story_title'];
            $text = strip_tags($text);
            //$text = preg_replace("`\[.*\]`U", "", $text);
            $text = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '', $text);
            $text = htmlentities($text, ENT_COMPAT, 'utf-8');
            $text = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $text);
            $text = stripslashes($text);
            $json['story_title'] = $text;

            $text = $story['story_short'] . ' ' . $story['story_text'];
            $text = strip_tags($text, '<br /><br>');
            $text = preg_replace('#<br\s*/?>#i', "\n", $text);
            //$text = preg_replace("`\[.*\]`U", "", $text);
            $text = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', "'", $text);
            $text = htmlentities($text, ENT_COMPAT, 'utf-8');
            $text = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $text);
            $text = stripslashes($text);
            $json['story_body'] = $text;

            if ($story['story_file'] > 0) {
                $fileInfo = array();
                $fileInfo['order'] = 'DESC';
                $fileInfo['sort'] = 'file_id';
                $fileInfo['start'] = 0;
                $fileInfo['content'] = $story['story_id'];
                $allfile = $file_handler->News_FileList($fileInfo);
                foreach ($allfile as $myfile) {
                    if ($myfile['file_type'] == 'mp4') {
                        $json['story_video'] = $myfile['fileurl'];
                    } elseif ($myfile['file_type'] == 'mp3') {
                        $json['story_audio'] = $myfile['fileurl'];
                    }
                }
            }

            $ret[] = $json;
            $return = json_encode($ret);
            unset($story);
            break;

        case 'singlegallery':
            $ret = array();
            $story_id = NewsUtils::News_UtilityCleanVars($_REQUEST, 'storyid', 0, 'int');
            $obj = $story_handler->get($story_id);
            $story = $obj->toArray();

            $json['story_id'] = $story['story_id'];
            $json['story_alias'] = $story['story_alias'];
            $json['story_publish'] = $story['story_publish'];
            $json['story_topic'] = $story['story_topic'];
            $json['story_img'] = $story['story_img'];
            $json['story_hits'] = $story['story_hits'];

            if ($story['story_topic'] > 0) {
                $topicObj = $topic_handler->get($story['story_topic']);
                $json['story_topic_title'] = $topicObj->getVar('topic_title');
            } else {
                $json['story_topic_title'] = '';
            }

            $text = $story['story_title'];
            $text = strip_tags($text);
            $text = preg_replace("`\[.*\]`U", "", $text);
            $text = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '', $text);
            $text = htmlentities($text, ENT_COMPAT, 'utf-8');
            $text = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $text);
            $text = stripslashes($text);
            $json['story_title'] = $text;

            $text = $story['story_short'] . ' ' . $story['story_text'];
            $text = strip_tags($text, '<img><a><br>');
            $text = preg_replace("/<a[^>]+\>(<img[^>]+\>)<\/a>/i", '$1', $text);
            $text = explode('<br />', $text);
            foreach ($text as $img) {
                $doc = new DOMDocument();
                $doc->loadHTML($img);
                $imageTags = $doc->getElementsByTagName('img');
                foreach ($imageTags as $tag) {
                    $image[] = $tag->getAttribute('src');
                }
            }
            $json['story_body'] = $image;

            $ret[] = $json;
            $return = json_encode($ret);
            unset($story);
            break;

        // vote to story
        case 'rate':
            if (xoops_getModuleOption('vote_active', 'news')) {
                $info = array();
                $info['story'] = NewsUtils::News_UtilityCleanVars($_POST, 'story', 0, 'int');
                $info['rate'] = NewsUtils::News_UtilityCleanVars($_POST, 'rate', 0, 'int');
                if ($info['story'] && $info['rate']) {
                    $return = $rate_handler->News_RateDo($info);
                }
            }
            break;
    }
    echo $return;
}
?>