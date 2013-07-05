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
 * News action script file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

function xoops_module_pre_install_news($module) {

    $indexFile = XOOPS_ROOT_PATH . "/uploads/index.html";
    $blankFile = XOOPS_ROOT_PATH . "/uploads/blank.gif";
    
    //Creation du fichier creator dans uploads
    $module_uploads = XOOPS_ROOT_PATH . "/uploads/news";
    if (!is_dir($module_uploads)) {
	    mkdir($module_uploads, 0777);
	    chmod($module_uploads, 0777);
	    copy($indexFile, XOOPS_ROOT_PATH . "/uploads/news/index.html");
    }

    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . "/uploads/news/image";
    if (!is_dir($module_uploads)) {
	    mkdir($module_uploads, 0777);
	    chmod($module_uploads, 0777);
	    copy($indexFile, XOOPS_ROOT_PATH . "/uploads/news/image/index.html");
	    copy($blankFile, XOOPS_ROOT_PATH . "/uploads/news/image/blank.gif");
    }
    
    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . "/uploads/news/image/original";
    if (!is_dir($module_uploads)) {
	    mkdir($module_uploads, 0777);
	    chmod($module_uploads, 0777);
	    copy($indexFile, XOOPS_ROOT_PATH . "/uploads/news/image/original/index.html");
	    copy($blankFile, XOOPS_ROOT_PATH . "/uploads/news/image/original/blank.gif");
    }
    
    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . "/uploads/news/image/medium";
    if (!is_dir($module_uploads)) {
	    mkdir($module_uploads, 0777);
	    chmod($module_uploads, 0777);
	    copy($indexFile, XOOPS_ROOT_PATH . "/uploads/news/image/medium/index.html");
	    copy($blankFile, XOOPS_ROOT_PATH . "/uploads/news/image/medium/blank.gif");
    }
    
    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . "/uploads/news/image/thumb";
    if (!is_dir($module_uploads)) {
	    mkdir($module_uploads, 0777);
	    chmod($module_uploads, 0777);
	    copy($indexFile, XOOPS_ROOT_PATH . "/uploads/news/image/thumb/index.html");
	    copy($blankFile, XOOPS_ROOT_PATH . "/uploads/news/image/thumb/blank.gif");
    }
    
    //Creation du fichier price dans uploads
    $module_uploads = XOOPS_ROOT_PATH . "/uploads/news/file";
    if (!is_dir($module_uploads)) {
	    mkdir($module_uploads, 0777);
	    chmod($module_uploads, 0777);
	    copy($indexFile, XOOPS_ROOT_PATH . "/uploads/news/file/index.html");
	    copy($blankFile, XOOPS_ROOT_PATH . "/uploads/news/file/blank.gif");
    }
   
    return true;
}
?>