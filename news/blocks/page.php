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
 * Module block page file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

require dirname(__FILE__) . '/header.php';


function news_page_show($options) {
    global $xoTheme, $xoopsTpl, $module_header;
    // Initialize content handler
    $story_handler = xoops_getmodulehandler ( 'story', 'news' );
    $topic_handler = xoops_getmodulehandler ( 'topic', 'news' );
    // Get the content menu
    $story = $story_handler->get($options[0]);
    // Add block data
    $block = $story->toArray();
    $topic = $topic_handler->get($block['story_topic']);
    $topic = $topic->toArray();
    $block['topic_id'] = $topic['topic_id'];
    $block['topic_title'] = $topic['topic_title'];
    $block['topic_alias'] = $topic['topic_alias'];
    $block['link'] = NewsUtils::News_UtilityStoryUrl( $block );
    $block['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) .' /medium/';
    $block['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) .' /thumb/';
    $block['width'] = xoops_getModuleOption('imgwidth', 'news');
    $block['float'] = xoops_getModuleOption('imgfloat', 'news');
    // Add styles
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/news/css/blocks.css', null);
    // Return block array
    return $block;
}

function news_page_edit($options) {
    require_once XOOPS_ROOT_PATH . '/modules/news/class/registry.php';
    $registry =& ForRegistry::getInstance();
    // Initialize content handler
    $story_handler = xoops_getmodulehandler('story', 'news');

    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('story_status', '1'));
    $story = $story_handler->getObjects($criteria);
    $form = _NEWS_MB_SELECTPAGE . '<select name="options[]">';
    foreach (array_keys($story) as $i) {
        $form .= '<option value="' . $story[$i]->getVar('story_id') . '"';
        if ($options[0] == $story[$i]->getVar('story_id')) {
            $form .= " selected='selected'";
        }
        $form .= ">" . $story[$i]->getVar('story_title') . "</option>\n";
    }
    $form .= "</select>\n";
    //$form .= "<input type='hidden' value='" . $options[1] . "'>\n";
    return $form;
}

?>