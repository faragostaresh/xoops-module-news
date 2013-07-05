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
 * News header file
 * Manage content page
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

if (!defined('XOOPS_ROOT_PATH')) {
    include_once '../../mainfile.php';
}

require_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
require_once XOOPS_ROOT_PATH . '/modules/news/class/perm.php';
require_once XOOPS_ROOT_PATH . '/modules/news/class/utils.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XOOPS_ROOT_PATH . "/class/pagenav.php";

// Initialize content handler
$story_handler = xoops_getmodulehandler('story', 'news');
$topic_handler = xoops_getmodulehandler('topic', 'news');
$file_handler = xoops_getmodulehandler('file', 'news');
$rate_handler = xoops_getmodulehandler('rate', 'news');
$perm_handler = NewsPermission::getHandler();
?>