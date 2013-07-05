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
require dirname(__FILE__) . '/header.php';
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
    $story_handler = xoops_getmodulehandler('story', 'news');
    $story = $story_handler->get($com_itemid);
    $com_replytitle = $story->getVar('story_title');
    include_once XOOPS_ROOT_PATH . '/include/comment_new.php';
}
?>