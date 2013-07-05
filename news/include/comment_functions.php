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
 * News
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */
// comment callback functions

function news_com_update($story_id, $story_comments) {
    $db =& Database::getInstance();
    $sql = 'UPDATE ' . $db->prefix('news_story') . ' SET story_comments = ' . $story_comments . ' WHERE story_id = ' . $story_id;
    $db->query($sql);
}

function news_com_approve() {
    // not yet
}

?>