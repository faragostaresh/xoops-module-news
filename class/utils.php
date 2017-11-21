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
 * News Utils class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */
class NewsUtils
{

    /**
     * Uploadimg public static function
     *
     * For manage all upload parts for images
     * Add topic , Edit topic , Add article , Edit article
     */
    public static function NewsUtilityUploadImg($type, $obj, $image)
    {
        include_once XOOPS_ROOT_PATH . "/class/uploader.php";
        $pach_original = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', 'news') . '/original/';
        $pach_medium = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', 'news') . '/medium/';
        $pach_thumb = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', 'news') . '/thumb/';

        $uploader_img = new XoopsMediaUploader ($pach_original, xoops_getModuleOption('img_mime', 'news'), xoops_getModuleOption('img_size', 'news'), xoops_getModuleOption('img_maxwidth', 'news'), xoops_getModuleOption('img_maxheight', 'news'));
        if ($uploader_img->fetchMedia($type)) {
            $uploader_img->setPrefix($type . '_');
            $uploader_img->fetchMedia($type);
            if (!$uploader_img->upload()) {
                $errors = $uploader_img->getErrors();
                self::NewsUtilityRedirect("javascript:history.go(-1)", 3, $errors);
                xoops_cp_footer();
                exit ();
            } else {
                $obj->setVar($type, $uploader_img->getSavedFileName());
                self::NewsUtilityResizePicture($pach_original . $uploader_img->getSavedFileName(), $pach_medium . $uploader_img->getSavedFileName(), xoops_getModuleOption('img_mediumwidth', 'news'), xoops_getModuleOption('img_mediumheight', 'news'));
                self::NewsUtilityResizePicture($pach_original . $uploader_img->getSavedFileName(), $pach_thumb . $uploader_img->getSavedFileName(), xoops_getModuleOption('img_thumbwidth', 'news'), xoops_getModuleOption('img_thumbheight', 'news'));
            }
        } else {
            if (isset ($image)) {
                $obj->setVar($type, $image);
            }
        }
    }

    /**
     * Deleteimg public static function
     *
     * For Deleteing uploaded images
     * Edit topic ,Edit article
     */
    public static function NewsUtilityDeleteImg($type, $obj)
    {
        if ($obj->getVar($type)) {

            // delete original image
            $currentPicture = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', 'news') . '/original/' . $obj->getVar($type);
            if (is_file($currentPicture) && file_exists($currentPicture)) {
                if (!unlink($currentPicture)) {
                    trigger_error("Error, impossible to delete the picture attached to this article");
                }
            }

            // delete original medium
            $currentPicture = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', 'news') . '/medium/' . $obj->getVar($type);
            if (is_file($currentPicture) && file_exists($currentPicture)) {
                if (!unlink($currentPicture)) {
                    trigger_error("Error, impossible to delete the picture attached to this article");
                }
            }

            // delete original thumb
            $currentPicture = XOOPS_ROOT_PATH . xoops_getModuleOption('img_dir', 'news') . '/thumb/' . $obj->getVar($type);
            if (is_file($currentPicture) && file_exists($currentPicture)) {
                if (!unlink($currentPicture)) {
                    trigger_error("Error, impossible to delete the picture attached to this article");
                }
            }
        }
        $obj->setVar($type, '');
    }

    /**
     * Uploadfile public static function
     *
     * For manage all upload parts for files
     */
    public static function NewsUtilityUploadFile($type, $obj, $file)
    {
        include_once XOOPS_ROOT_PATH . "/class/uploader.php";
        $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH . xoops_getModuleOption('file_dir', 'news'), explode('|', xoops_getModuleOption('file_mime', 'news')), xoops_getModuleOption('file_size', 'news'));
        if ($uploader->fetchMedia($type)) {
            $uploader->setPrefix($type . '_');
            $uploader->fetchMedia($type);
            if ($uploader->upload()) {
                $obj->setVar($type, $uploader->getSavedFileName());
                $obj->setVar('file_type', preg_replace('/^.*\./', '', $uploader->getSavedFileName()));
            } else {
                echo _AM_UPLOAD_ERROR . ' ' . $uploader->getErrors();
            }
        } else {
            $errors = $uploader->getErrors();
            self::NewsUtilityRedirect("javascript:history.go(-1)", 3, $errors);
            xoops_cp_footer();
            exit ();
        }
    }

    /**
     *
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Gregory Mage (Aka Mage)
     */
    public static function NewsUtilityBreadcrumb($lasturl, $breadcrumbtitle, $topic_id, $prefix = ' &raquo; ', $title = 'topic_title')
    {
        $breadcrumb = '';
        include_once XOOPS_ROOT_PATH . '/modules/news/class/topic.php';
        require_once $GLOBALS ['xoops']->path('/class/tree.php');
        $topic_Handler = xoops_getModuleHandler("topic", "news");

        $criteria = new CriteriaCompo ();
        $topics_arr = $topic_Handler->getAll($criteria);
        $mytree = new XoopsObjectTree ($topics_arr, 'topic_id', 'topic_pid');

        if (xoops_getModuleOption('bc_tohome', 'news')) {
            $breadcrumb = '<a title="' . _NEWS_MD_HOME . '" href="' . XOOPS_URL . '">' . _NEWS_MD_HOME . '</a>' . $prefix;
        }
        $breadcrumb = $breadcrumb . self::NewsUtilityPathTreeUrl($mytree, $topic_id, $topics_arr, $title, $prefix, true, 'ASC', $lasturl, xoops_getModuleOption('bc_modname', 'news'));
        if ($lasturl) {
            $breadcrumb = $breadcrumb . $prefix . $breadcrumbtitle;
        }
        return $breadcrumb;
    }

    /**
     *
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Gregory Mage (Aka Mage)
     */
    public static function NewsUtilityPathTreeUrl($mytree, $key, $topic_array, $title, $prefix = ' &raquo; ', $link = false, $order = 'ASC', $lasturl = false, $modname)
    {
        global $xoopsModule;
        $topic_parent = $mytree->getAllParent($key);
        if ($order == 'ASC') {
            $topic_parent = array_reverse($topic_parent);
            if ($link == true && $modname) {
                if ($key) {
                    $Path = '<a title="' . $xoopsModule->name() . '" href="' . XOOPS_URL . '/modules/news/index.php">' . $xoopsModule->name() . '</a>' . $prefix;
                } else {
                    $Path = '<a title="' . $xoopsModule->name() . '" href="' . XOOPS_URL . '/modules/news/index.php">' . $xoopsModule->name() . '</a>';
                }
            } elseif ($modname) {
                $Path = $xoopsModule->name() . $prefix;
            } else {
                $Path = '';
            }
        } else {
            if (array_key_exists($key, $topic_array)) {
                $first_category = $topic_array [$key]->getVar($title);
            } else {
                $first_category = '';
            }
            $Path = $first_category . $prefix;
        }
        foreach (array_keys($topic_parent) as $j) {
            if ($link == true) {
                $topic_info = [
                    'topic_id'    => $topic_parent [$j]->getVar('topic_id'),
                    'topic_title' => $topic_parent [$j]->getVar('topic_title'),
                    'topic_alias' => $topic_parent [$j]->getVar('topic_alias'),
                ];
                $Path .= '<a title="' . $topic_parent [$j]->getVar($title) . '" href="' . self::NewsUtilityTopicUrl($topic_info) . '">' . $topic_parent [$j]->getVar($title) . '</a>' . $prefix;
            } else {
                $Path .= $topic_parent [$j]->getVar($title) . $prefix;
            }
        }
        if ($order == 'ASC') {
            if (array_key_exists($key, $topic_array)) {
                if ($lasturl == true) {
                    $first_category = '<a title="' . $topic_array [$key]->getVar($title) . '" href="' . self::NewsUtilityTopicUrl(['topic_id' => $topic_array [$key]->getVar('topic_id'), 'topic_alias' => $topic_array [$key]->getVar('topic_alias')]) . '">' . $topic_array [$key]->getVar($title) . '</a>';
                } else {
                    $first_category = $topic_array [$key]->getVar($title);
                }
            } else {
                $first_category = '';
            }
            $Path .= $first_category;
        } else {
            if ($link == true) {
                $Path .= '<a title="' . $xoopsModule->name() . '" href="' . XOOPS_URL . '/modules/news/index.php">' . $xoopsModule->name() . '</a>';
            } else {
                $Path .= $xoopsModule->name();
            }
        }
        return $Path;
    }

    /**
     * Homepage public static function
     * For management module index page
     * Types:
     * list all contents from all topics whit out topic list
     * List all topics
     * List all static pages
     * Show selected content
     */
    public static function NewsUtilityHomePage($story_infos, $type)
    {

        $story_handler = xoops_getmodulehandler('story', 'news');
        $topic_handler = xoops_getmodulehandler('topic', 'news');
        if (!$type) {
            $type = 'type1';
        }
        $stores = [];

        switch ($type) {

            // list all contents from all topics whit out topic list
            case 'type1' :
                $stores ['content'] = $story_handler->NewsStoryList($story_infos);
                $stores ['numrows'] = $story_handler->NewsStoryCount($story_infos);
                if ($stores ['numrows'] > $story_infos ['story_limit']) {
                    if ($story_infos ['story_topic']) {
                        $story_pagenav = new XoopsPageNav ($stores ['numrows'], $story_infos ['story_limit'], $story_infos ['story_start'], 'start', 'limit=' . $story_infos ['story_limit'] . '&storytopic=' . $story_infos ['story_topic']);
                    } else {
                        $story_pagenav = new XoopsPageNav ($stores ['numrows'], $story_infos ['story_limit'], $story_infos ['story_start'], 'start', 'limit=' . $story_infos ['story_limit']);
                    }
                    $stores ['pagenav'] = $story_pagenav->renderNav(4);
                } else {
                    $stores ['pagenav'] = '';
                }
                break;

            // List all topics
            case 'type2' :
                $topic_order = xoops_getModuleOption('admin_showorder_topic', 'news');
                $topic_sort = xoops_getModuleOption('admin_showsort_topic', 'news');
                $topic_parent = $story_infos ['story_topic'];
                $stores ['content'] = $topic_handler->NewsTopicList(null, 0, $topic_order, $topic_sort, null, 1, $topic_parent);
                $stores ['pagenav'] = null;
                break;

            // List all static pages
            case 'type3' :
                if (!$story_infos ['story_topic']) {
                    $story_infos ['story_topic'] = 0;
                }
                $story_infos ['story_subtopic'] = null;
                $story_infos ['story_static'] = 0;
                $story_infos ['admin_side'] = 1;

                $stores ['content'] = $story_handler->NewsStoryList($story_infos);
                $stores ['numrows'] = $story_handler->NewsStoryCount($story_infos);
                if ($stores ['numrows'] > $story_infos ['story_limit']) {
                    if ($story_topic) {
                        $story_pagenav = new XoopsPageNav ($stores ['numrows'], $story_infos ['story_limit'], $story_infos ['story_start'], 'start', 'limit=' . $story_infos ['story_limit'] . '&storytopic=' . $story_infos ['story_topic']);
                    } else {
                        $story_pagenav = new XoopsPageNav ($stores ['numrows'], $story_infos ['story_limit'], $story_infos ['story_start'], 'start', 'limit=' . $story_infos ['story_limit']);
                    }
                    $stores ['pagenav'] = $story_pagenav->renderNav(4);
                } else {
                    $stores ['pagenav'] = '';
                }
                break;

            // Show selected static content
            case 'type4' :
                if ($story_infos['id'] && $story_infos['title'] && $story_infos['alias']) {
                    $id = $story_infos['id'];
                    $title = $story_infos['title'];
                    $alias = $story_infos['alias'];
                } else {
                    $id = 0;
                    $title = xoops_getModuleOption('static_name', 'news');
                    $alias = self::NewsUtilityAliasFilter(xoops_getModuleOption('static_name', 'news'));
                }
                $default_info = ['id' => $id, 'title' => $title, 'alias' => $alias];
                $stores ['content'] = $story_handler->NewsStoryDefault($default_info);
                break;
        }
        return $stores;
    }

    /**
     * Verify that a field exists inside a mysql table
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     */
    public static function NewsUtilityFieldExists($fieldname, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * Add a field to a mysql table
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     */
    public static function NewsUtilityAddField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF('ALTER TABLE ' . $table . ' ADD ' . $field);
        return $result;
    }

    /**
     * DROP a field from a mysql table
     */
    public static function NewsUtilityDropField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF('ALTER TABLE ' . $table . ' DROP ' . $field);
        return $result;
    }

    /**
     * Verify that a mysql table exists
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     */
    public static function NewsUtilityTableExists($tablename)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * Add a table
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     */
    public static function NewsUtilityAddTable($query)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF($query);
        return $result;
    }

    /**
     * Resize a Picture to some given dimensions (using the wideImage library)
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (ttp://www.instant-zero.com)
     *
     * @param string $src_path Picture's source
     * @param string $dst_path Picture's destination
     * @param integer $param_width Maximum picture's width
     * @param integer $param_height Maximum picture's height
     * @param boolean $keep_original Do we have to keep the original picture ?
     * @param string $fit Resize mode (see the wideImage library for more information)
     */
    public static function NewsUtilityResizePicture($src_path, $dst_path, $param_width, $param_height, $keep_original = true, $fit = 'inside')
    {
        require XOOPS_ROOT_PATH . '/modules/news/class/wideimage/WideImage.inc.php';
        $resize = true;
        echo $src_path;
        $pictureDimensions = getimagesize($src_path);
        if (is_array($pictureDimensions)) {
            $pictureWidth = $pictureDimensions[0];
            $pictureHeight = $pictureDimensions[1];
            if ($pictureWidth < $param_width && $pictureHeight < $param_height) {
                $resize = false;
            }
        }

        $img = wiImage::load($src_path);
        if ($resize) {
            $result = $img->resize($param_width, $param_height, $fit);
            $result->saveToFile($dst_path);
        } else {
            copy($src_path, $dst_path);
        }
        if (!$keep_original) {
            unlink($src_path);
        }
        return true;
    }

    /**
     *  Rebuild
     */
    public static function NewsUtilityRebuild($handler, $item_id, $op, $set, $get, $start_id, $end_id)
    {
        // check last_id
        $criteria = new CriteriaCompo ();
        $criteria->setSort($item_id);
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $last = $handler->getObjects($criteria);
        foreach ($last as $item) {
            $last_id = $item->getVar($item_id);
        }

        // set end_id
        $end_id = $end_id + 250;

        // do rebuild
        while ($start_id <= $end_id) {
            $obj = $handler->get($start_id);
            if ($obj) {
                $new = self::NewsUtilityDoRebuild($op, $obj->getVar($get, 'e'));
                $obj->setVar($set, $new);
                $handler->insert($obj);
            }
            $start_id = $start_id + 1;
        }


        // Redirect
        if ($start_id <= $last_id) {
            self::NewsUtilityRedirect('tools.php?op=' . $op . '&start_id=' . $start_id . '&end_id=' . $end_id, 3, _NEWS_AM_MSG_INPROC);
            xoops_cp_footer();
            exit ();
        }

    }

    /**
     *  Make text for Rebuild
     */
    public static function NewsUtilityDoRebuild($op, $get)
    {
        switch ($op) {
            case 'alias':
                $item = self::NewsUtilityAliasFilter($get);
                break;

            case 'topicalias':
                $item = self::NewsUtilityAliasFilter($get);
                break;

            case 'keyword':
                $item = self::NewsUtilityMetaFilter($get);
                break;

            case 'description':
                $item = self::NewsUtilityAjaxFilter($get);
                break;
        }
        return $item;
    }

    /**
     * Get variables passed by GET or POST method
     *
     */
    public static function NewsUtilityCleanVars(&$global, $key, $default = '', $type = 'int')
    {

        switch ($type) {
            case 'array':
                $ret = (isset($global[$key]) && is_array($global[$key])) ? $global[$key] : $default;
                break;
            case 'date':
                $ret = (isset($global[$key])) ? strtotime($global[$key]) : $default;
                break;
            case 'string':
                $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
                break;
            case 'int':
            default:
                $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
                break;
        }
        if ($ret === false) {
            return $default;
        }
        return $ret;
    }

    /**
     * Check html editors
     *
     */
    public static function NewsUtilityEditorHTML()
    {
        $editor = xoops_getModuleOption('form_editor', 'news');
        if (isset($editor) && in_array($editor, ['tinymce', 'fckeditor', 'koivi', 'inbetween', 'spaw', 'ckeditor'])) {
            return true;
        }
        return false;
    }

    /**
     * Replace all escape, character, ... for display a correct url
     *
     * @String  $url    string to transform
     * @String  $type   string replacement for any blank case
     * @return  $url
     */
    public static function NewsUtilityAliasFilter($url, $type = '', $module = 'news')
    {

        // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
        $regular_expression = xoops_getModuleOption('regular_expression', $module);

        $url = strip_tags($url);
        $url = preg_replace("`\[.*\]`U", "", $url);
        $url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
        $url = htmlentities($url, ENT_COMPAT, 'utf-8');
        $url = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $url);
        $url = preg_replace([$regular_expression, "`[-]+`"], "-", $url);
        $url = ($url == "") ? $type : strtolower(trim($url, '-'));
        return $url;
    }

    /**
     * Replace all escape, character, ... for display a correct Meta
     *
     * @String  $meta    string to transform
     * @String  $type   string replacement for any blank case
     * @return  $meta
     */
    public static function NewsUtilityMetaFilter($meta, $type = '', $module = 'news')
    {

        // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
        $regular_expression = xoops_getModuleOption('regular_expression', $module);

        $meta = strip_tags($meta);
        $meta = preg_replace("`\[.*\]`U", "", $meta);
        $meta = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', ',', $meta);
        $meta = htmlentities($meta, ENT_COMPAT, 'utf-8');
        $meta = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $meta);
        $meta = preg_replace([$regular_expression, "`[,]+`"], ",", $meta);
        $meta = ($meta == "") ? $type : strtolower(trim($meta, ','));
        return $meta;
    }

    /**
     * Replace all escape, character, ... for display a correct text
     *
     * @String  $text    string to transform
     * @String  $type   string replacement for any blank case
     * @return  $text
     */
    public static function NewsUtilityAjaxFilter($text, $type = '')
    {
        $text = strip_tags($text);
        $text = preg_replace("`\[.*\]`U", "", $text);
        $text = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $text);
        $text = htmlentities($text, ENT_COMPAT, 'utf-8');
        $text = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $text);
        $text = stripslashes($text);
        return $text;
    }

    /**
     * Build Redirect page
     */
    public static function NewsUtilityRedirect($url, $time = 3, $message = '')
    {
        /* global $xoopsModule;
        if (preg_match("/[\\0-\\31]|about:|script:/i", $url)) {
            if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
                $url = XOOPS_URL;
            }
        }
        // Create Template instance
        $tpl = new XoopsTpl();
        // Assign Vars
        $tpl->assign('url', $url);
        $tpl->assign('time', $time);
        $tpl->assign('message', $message);
        $tpl->assign('ifnotreload', sprintf(_IFNOTRELOAD, $url));
        // Call template file
        echo $tpl->fetch(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/templates/admin/news_redirect.tpl');
        // Force redirection
        header("refresh: " . $time . "; url=" . $url); */
        redirect_header($url, $time, $message);
    }

    /**
     * Build Message
     */
    public static function NewsUtilityMessage($page, $message = '', $id, $handler)
    {
        global $xoopsModule;
        $tpl = new XoopsTpl();
        //ob_start();
        $tpl->assign('message', $message);
        $tpl->assign('id', $id);
        $tpl->assign('url', $page);
        $tpl->assign('handler', $handler);
        $tpl->assign('ifnotreload', sprintf(_IFNOTRELOAD, $page));
        echo $tpl->fetch(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/templates/admin/news_confirm.tpl');
        //ob_flush();
    }

    /**
     * Build topic URL
     */
    public static function NewsUtilityTopicUrl($array)
    {
        $lenght_id = xoops_getModuleOption('lenght_id', 'news');
        $friendly_url = xoops_getModuleOption('friendly_url', 'news');
        if ($lenght_id != 0) {
            $id = $array['topic_id'];
            while (strlen($id) < $lenght_id)
                $id = "0" . $id;
        } else {
            $id = $array['topic_id'];
        }

        switch ($friendly_url) {

            case 'none':
                $rewrite_base = '/modules/';
                $page = 'page=' . $array['topic_alias'];
                return XOOPS_URL . $rewrite_base . '/news/index.php?storytopic=' . $id . '&amp;' . $page;
                break;

            case 'rewrite':
                $rewrite_base = xoops_getModuleOption('rewrite_mode', 'news');
                $rewrite_ext = xoops_getModuleOption('rewrite_ext', 'news');
                $module_name = '';
                if (xoops_getModuleOption('rewrite_name', 'news')) {
                    $module_name = xoops_getModuleOption('rewrite_name', 'news') . '/';
                }
                $page = $array['topic_alias'];
                $type = xoops_getModuleOption('topic_name', 'news') . '/';
                $id = $id . '/';
                return XOOPS_URL . $rewrite_base . $module_name . $type . $id . $page . $rewrite_ext;
                break;

            case 'short':
                $rewrite_base = xoops_getModuleOption('rewrite_mode', 'news');
                $rewrite_ext = xoops_getModuleOption('rewrite_ext', 'news');
                $module_name = '';
                if (xoops_getModuleOption('rewrite_name', 'news')) {
                    $module_name = xoops_getModuleOption('rewrite_name', 'news') . '/';
                }
                $page = $array['topic_alias'];
                $type = xoops_getModuleOption('topic_name', 'news') . '/';
                return XOOPS_URL . $rewrite_base . $module_name . $type . $page . $rewrite_ext;
                break;

            case 'id':
                return XOOPS_URL . '/modules/news/index.php?storytopic=' . $id;
                break;

            case 'topic':
                return XOOPS_URL . '/modules/news/index.php?storytopic=' . $id;
                break;
        }

    }

    /**
     * Build Item URL
     */
    public static function NewsUtilityStoryUrl($array, $type = 'article')
    {
        $comment = '';
        $lenght_id = xoops_getModuleOption('lenght_id', 'news');
        $friendly_url = xoops_getModuleOption('friendly_url', 'news');

        if ($lenght_id != 0) {
            $id = $array['story_id'];
            while (strlen($id) < $lenght_id)
                $id = "0" . $id;
        } else {
            $id = $array['story_id'];
        }

        if (isset($array['topic_alias']) && $array['topic_alias']) {
            $topic_name = $array['topic_alias'];
        } else {
            $topic_name = self::NewsUtilityAliasFilter(xoops_getModuleOption('static_name', 'news'));
        }

        switch ($friendly_url) {

            case 'none':
                if ($topic_name) {
                    $topic_name = 'topic=' . $topic_name . '&amp;';
                }
                $rewrite_base = '/modules/';
                $page = 'page=' . $array['story_alias'];
                return XOOPS_URL . $rewrite_base . 'news' . '/' . $type . '.php?' . $topic_name . 'storyid=' . $id . '&amp;' . $page . $comment;
                break;

            case 'rewrite':
                if ($topic_name) {
                    $topic_name = $topic_name . '/';
                }
                $rewrite_base = xoops_getModuleOption('rewrite_mode', 'news');
                $rewrite_ext = xoops_getModuleOption('rewrite_ext', 'news');
                $module_name = '';
                if (xoops_getModuleOption('rewrite_name', 'news')) {
                    $module_name = xoops_getModuleOption('rewrite_name', 'news') . '/';
                }
                $page = $array['story_alias'];
                $type = $type . '/';
                $id = $id . '/';
                if ($type == 'article/') $type = '';

                if ($type == 'comment-edit/' || $type == 'comment-reply/' || $type == 'comment-delete/') {
                    return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
                }

                return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $id . $page . $rewrite_ext;
                break;

            case 'short':
                if ($topic_name) {
                    $topic_name = $topic_name . '/';
                }
                $rewrite_base = xoops_getModuleOption('rewrite_mode', 'news');
                $rewrite_ext = xoops_getModuleOption('rewrite_ext', 'news');
                $module_name = '';
                if (xoops_getModuleOption('rewrite_name', 'news')) {
                    $module_name = xoops_getModuleOption('rewrite_name', 'news') . '/';
                }
                $page = $array['story_alias'];
                $type = $type . '/';
                if ($type == 'article/') $type = '';

                if ($type == 'comment-edit/' || $type == 'comment-reply/' || $type == 'comment-delete/') {
                    return XOOPS_URL . $rewrite_base . $module_name . $type . $id . '/';
                }

                return XOOPS_URL . $rewrite_base . $module_name . $type . $topic_name . $page . $rewrite_ext;
                break;

            case 'id':
                return XOOPS_URL . '/modules/news/' . $type . '.php?storyid=' . $id;
                break;

            case 'topic':
                return XOOPS_URL . '/modules/news/' . $type . '.php?storytopic=' . $array['story_topic'] . '&amp;storyid=' . $id;
                break;
        }
    }

    public static function NewsUtilityGetTree($elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['topic_pid'] == $parentId) {
                $children = self::NewsUtilityGetTree($elements, $element['topic_id']);
                if ($children) {
                    $element['topic_children'] = $children;
                }
                $branch[$element['topic_id']] = $element;
                unset($elements[$element['topic_id']]);
            }
        }
        return $branch;
    }

    public static function NewsUtilityCurrentUserID()
    {
        global $xoopsUser;
        $uid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
        return $uid;
    }
}

?>