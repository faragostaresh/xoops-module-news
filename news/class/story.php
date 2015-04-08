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
 * News story class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

class news_story extends XoopsObject {
    
    public $db;
    public $table;
    
    /**
     * Class constructor
     */
    public function news_story() {
        $this->initVar ( 'story_id', XOBJ_DTYPE_INT );
        $this->initVar ( 'story_title', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_subtitle', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_topic', XOBJ_DTYPE_INT );
        $this->initVar ( 'story_type', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_short', XOBJ_DTYPE_TXTAREA, '' );
        $this->initVar ( 'story_text', XOBJ_DTYPE_TXTAREA, '' );
        $this->initVar ( 'story_link', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_words', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_desc', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_alias', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_status', XOBJ_DTYPE_INT, 1 );
        $this->initVar ( 'story_slide', XOBJ_DTYPE_INT, 0 );
        $this->initVar ( 'story_marquee', XOBJ_DTYPE_INT, 0 );      
        $this->initVar ( 'story_important', XOBJ_DTYPE_INT, 0 );
        $this->initVar ( 'story_default', XOBJ_DTYPE_INT, 0 );
        $this->initVar ( 'story_create', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_update', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_publish', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_expire', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_uid', XOBJ_DTYPE_INT, 0 );
        $this->initVar ( 'story_author', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_source', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_hits', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_img', XOBJ_DTYPE_TXTBOX, '' );
        $this->initVar ( 'story_comments', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_file', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_rating', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'story_votes', XOBJ_DTYPE_INT, '' );
        $this->initVar ( 'dohtml', XOBJ_DTYPE_INT, 1 );
        $this->initVar ( 'doxcode', XOBJ_DTYPE_INT, 1 );
        $this->initVar ( 'dosmiley', XOBJ_DTYPE_INT, 1 );
        $this->initVar ( 'doimage', XOBJ_DTYPE_INT, 1 );
        $this->initVar ( 'dobr', XOBJ_DTYPE_INT, 0 );
        
        $this->db = $GLOBALS ['xoopsDB'];
        $this->table = $this->db->prefix ( 'news_story' );
    }
    
    /**
     * Submit form in admin side
     */
    public function News_StoryForm($story_type = 'news') {
        $form = new XoopsThemeForm ( _NEWS_AM_STORY_FORM, 'news', 'backend.php', 'post' );
        $form->setExtra ( 'enctype="multipart/form-data"' );
        
        if ($this->isNew ()) {
            $form->addElement ( new XoopsFormHidden ( 'op', 'add' ) );
            $form->addElement ( new XoopsFormHidden ( 'story_uid', $GLOBALS ['xoopsUser']->getVar ( 'uid' ) ) );
        } else {
            $form->addElement ( new XoopsFormHidden ( 'op', 'edit' ) );
            $story_type = $this->getVar ( 'story_type', 'e' );
        }
        // Content Id
        $form->addElement ( new XoopsFormHidden ( 'story_id', $this->getVar ( 'story_id', 'e' ) ) );
        // Content type
        $form->addElement ( new XoopsFormHidden ( 'story_type', $story_type ) );
        // Content title
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_FORMTITLE, 'story_title', 50, 255, $this->getVar ( 'story_title', 'e' ) ), true );
        // Content alias text
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_FORMALIAS, 'story_alias', 50, 255, $this->getVar ( 'story_alias', 'e' ) ), true );
        // subtitle
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_SUBTITLE, 'story_subtitle', 50, 255, $this->getVar ( 'story_subtitle', 'e' ) ), false );
        // Topic
        $topic_Handler = xoops_getModuleHandler ( "topic", "news" );
        $criteria = new CriteriaCompo ();
        $topic = $topic_Handler->getObjects ( $criteria );
        if ($topic) {
            $tree = new XoopsObjectTree ( $topic, 'topic_id', 'topic_pid' );
            ob_start ();
            echo $tree->makeSelBox ( 'story_topic', 'topic_title', '--', $this->getVar ( 'story_topic', 'e' ), true );
            $topic_sel = new XoopsFormLabel ( _NEWS_AM_STORY_TOPIC, ob_get_contents () );
            $topic_sel->setDescription ( _NEWS_AM_STORY_TOPIC_DESC );
            $form->addElement ( $topic_sel );
            ob_end_clean ();
        } else {
            $form->addElement ( new XoopsFormHidden ( 'story_topic', 0 ) );
        }   
        // Short
        $Short_editor_tray = new XoopsFormElementTray ( _NEWS_AM_STORY_SHORT, '<br />' );
        if (class_exists ( 'XoopsFormEditor' )) {
            $configs = array ('name' => 'story_desc', 'value' => $this->getVar ( 'story_short', 'e' ), 'rows' => 25, 'cols' => 90, 'width' => '100%', 'height' => '400px', 'editor' => xoops_getModuleOption ( 'form_editor', 'news' ) );
            $Short_editor_tray->addElement ( new XoopsFormEditor ( '', 'story_short', $configs, false, $onfailure = 'textarea' ) );
        } else {
            $Short_editor_tray->addElement ( new XoopsFormDhtmlTextArea ( '', 'story_short', $this->getVar ( 'story_short', 'e' ), '100%', '100%' ) );
        }
        $form->addElement ($Short_editor_tray);
        // Text
        $text_editor_tray = new XoopsFormElementTray ( _NEWS_AM_STORY_FORMTEXT, '<br />' );
        if (class_exists ( 'XoopsFormEditor' )) {
            $configs = array ('name' => 'story_desc', 'value' => $this->getVar ( 'story_text', 'e' ), 'rows' => 25, 'cols' => 90, 'width' => '100%', 'height' => '400px', 'editor' => xoops_getModuleOption ( 'form_editor', 'news' ) );
            $text_editor_tray->addElement ( new XoopsFormEditor ( '', 'story_text', $configs, false, $onfailure = 'textarea' ) );
        } else {
            $text_editor_tray->addElement ( new XoopsFormDhtmlTextArea ( '', 'story_text', $this->getVar ( 'story_text', 'e' ), '100%', '100%' ) );
        }
        $text_editor_tray->setDescription ( _NEWS_AM_STORY_FORMTEXT_DESC );
        $form->addElement ($text_editor_tray);
        //tag
        if ((xoops_getModuleOption ( 'usetag', 'news' )) and (is_dir ( XOOPS_ROOT_PATH . '/modules/tag' ))) {
            $items_id = $this->isNew () ? 0 : $this->getVar ( "story_id" );
            include_once XOOPS_ROOT_PATH . "/modules/tag/include/formtag.php";
            $form->addElement ( new XoopsFormTag ( "item_tag", 60, 255, $items_id, $catid = 0 ) );
        }
        // options
        $options = new XoopsFormElementTray ( _NEWS_AM_STORY_OPTIONS, '<br />' );
        if (! NewsUtils::News_UtilityEditorHTML (  )) {
            if ($this->isNew ()) {
                $this->setVar ( 'dohtml', 0 );
                $this->setVar ( 'dobr', 1 );
            }
            // HTML
            $html_checkbox = new XoopsFormCheckBox ( '', 'dohtml', $this->getVar ( 'dohtml', 'e' ) );
            $html_checkbox->addOption ( 1, _NEWS_AM_STORY_DOHTML );
            $options->addElement ( $html_checkbox );
            // Break line
            $breaks_checkbox = new XoopsFormCheckBox ( '', 'dobr', $this->getVar ( 'dobr', 'e' ) );
            $breaks_checkbox->addOption ( 1, _NEWS_AM_STORY_BREAKS );
            $options->addElement ( $breaks_checkbox );
        } else {
            $form->addElement ( new xoopsFormHidden ( 'dohtml', 1 ) );
            $form->addElement ( new xoopsFormHidden ( 'dobr', 0 ) );
        }
        // Xoops Image
        $doimage_checkbox = new XoopsFormCheckBox ( '', 'doimage', $this->getVar ( 'doimage', 'e' ) );
        $doimage_checkbox->addOption ( 1, _NEWS_AM_STORY_DOIMAGE );
        $options->addElement ( $doimage_checkbox );
        // Xoops Code
        $xcodes_checkbox = new XoopsFormCheckBox ( '', 'doxcode', $this->getVar ( 'doxcode', 'e' ) );
        $xcodes_checkbox->addOption ( 1, _NEWS_AM_STORY_DOXCODE );
        $options->addElement ( $xcodes_checkbox );
        // Xoops Smiley
        $smiley_checkbox = new XoopsFormCheckBox ( '', 'dosmiley', $this->getVar ( 'dosmiley', 'e' ) );
        $smiley_checkbox->addOption ( 1, _NEWS_AM_STORY_DOSMILEY );
        $options->addElement ( $smiley_checkbox );
        // Editor and options
        $form->addElement ($options);
        // Image
        $story_img = $this->getVar ( 'story_img' ) ? $this->getVar ( 'story_img' ) : 'blank.gif';
        $uploadirectory_story_img = xoops_getModuleOption ( 'img_dir', 'news' ) . '/original';
        $uploadirectory_thumb_img = xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/';
        $fileseltray_story_img = new XoopsFormElementTray ( _NEWS_AM_GLOBAL_IMG, '<br />' );
        $fileseltray_story_img->addElement ( new XoopsFormLabel ( '', "<img class='fromimage' src='" . XOOPS_URL . $uploadirectory_thumb_img . $story_img . "' name='image_story_img' id='image_story_img' alt='' />" ) );
        if ($this->getVar ( 'story_img' )) {
            $delete_img = new XoopsFormCheckBox ( '', 'deleteimage', 0 );
            $delete_img->addOption ( 1, _DELETE );
            $fileseltray_story_img->addElement ( $delete_img );
        }
        $fileseltray_story_img->addElement ( new XoopsFormFile ( _NEWS_AM_GLOBAL_FORMUPLOAD, 'story_img', xoops_getModuleOption ( 'img_size', 'news' ) ), false );
        $form->addElement ( $fileseltray_story_img );
        // Files
        $uploadirectory_file = xoops_getModuleOption ( 'file_dir', 'news' );
        $fileseltray_file = new XoopsFormFile ( _NEWS_AM_FILE_SELECT, 'file_name', xoops_getModuleOption ( 'file_size', 'news' ) );
        $file = new XoopsFormElementTray ( _NEWS_AM_FILE );
        $file->addElement ( $fileseltray_file );
        $file->setDescription ( _NEWS_AM_STORY_FILE_DESC );
        $form->addElement ($file);
        // Metas
        $form->addElement ( new XoopsFormTextArea ( 'Metas Keyword', 'story_words', $this->getVar ( 'story_words', 'e' ), 3, 50 ) );
        $form->addElement ( new XoopsFormTextArea ( 'Metas Description', 'story_desc', $this->getVar ( 'story_desc', 'e' ), 3, 50 ) );
        // Content author
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_FORMAUTHOR, 'story_author', 50, 255, $this->getVar ( 'story_author', 'e' ) ), false );
        // Content Source
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_FORMSOURCE, 'story_source', 50, 255, $this->getVar ( 'story_source', 'e' ) ), false );
      // publish and expire date
        $date_option = new XoopsFormElementTray(_NEWS_AM_STORY_PEDATE,'<br />');
       $check = $this->getVar ( 'story_create' ) != $this->getVar ( 'story_publish' ) ? 1 :0;
       $publish_checkbox = new XoopsFormCheckBox('', 'autopublish', $check);
       $publish_checkbox->addOption(1, _NEWS_AM_STORY_SETDATETIME);
       $date_option->addElement($publish_checkbox);
       $date_option->addElement(new XoopsFormDateTime(_NEWS_AM_STORY_SETDATETIME, 'story_publish', 15,  $this->getVar ( 'story_publish' )));
       $check = $this->getVar ( 'story_expire' ) > 0 ? 1 :0;
       $expir_checkbox = new XoopsFormCheckBox('', 'autoexpire', $check);
       $expir_checkbox ->addOption(1, _NEWS_AM_STORY_SETEXPDATETIME);
       $date_option->addElement($expir_checkbox );
       $date_option->addElement(new XoopsFormDateTime(_NEWS_AM_STORY_SETEXPDATETIME, 'story_expire', 15,  $this->getVar ( 'story_expire' )));
      $form->addElement($date_option);
        // Active
        $form->addElement ( new XoopsFormRadioYN ( _NEWS_AM_STORY_FORMACTIF, 'story_status', $this->getVar ( 'story_status', 'e' ) ) );
        // Default
        $form->addElement ( new XoopsFormRadioYN ( _NEWS_AM_STORY_FORMDEFAULT, 'story_default', $this->getVar ( 'story_default', 'e' ) ) );
        // Important
        $form->addElement ( new XoopsFormRadioYN ( _NEWS_AM_STORY_IMPORTANT, 'story_important', $this->getVar ( 'story_important', 'e' ) ) );
        // Slide
        $form->addElement ( new XoopsFormRadioYN ( _NEWS_AM_STORY_SLIDE, 'story_slide', $this->getVar ( 'story_slide', 'e' ) ) );
      // Marque
        $form->addElement ( new XoopsFormRadioYN ( _NEWS_AM_STORY_MARQUE, 'story_marquee', $this->getVar ( 'story_marquee', 'e' ) ) );
        // Submit buttons
        $button_tray = new XoopsFormElementTray ( '', '' );
        $submit_btn = new XoopsFormButton ( '', 'post', _SUBMIT, 'submit' );
        $button_tray->addElement ( $submit_btn );
        $cancel_btn = new XoopsFormButton ( '', 'cancel', _CANCEL, 'cancel' );
        $cancel_btn->setExtra ( 'onclick="javascript:history.go(-1);"' );
        $button_tray->addElement ( $cancel_btn );
        $form->addElement ( $button_tray );
        $form->display ();
        
        return $form;
    }
    
    /**
     * Submit form in admin side
     */
    public function News_StorySimpleForm($story_type = 'news') {
        $form = new XoopsThemeForm ( _NEWS_AM_STORY_FORM, 'news', 'submit.php', 'post' );
        $form->setExtra ( 'enctype="multipart/form-data"' );
        
        if ($this->isNew ()) {
            $form->addElement ( new XoopsFormHidden ( 'op', 'add' ) );
            $form->addElement ( new XoopsFormHidden ( 'story_uid', $GLOBALS ['xoopsUser']->getVar ( 'uid' ) ) );
        } else {
            $groups = explode ( " ", $this->getVar ( 'story_groups', 'e' ) );
            $form->addElement ( new XoopsFormHidden ( 'op', 'edit' ) );
            $story_type = $this->getVar ( 'story_type', 'e' );
        }
        // Content Id
        $form->addElement ( new XoopsFormHidden ( 'story_id', $this->getVar ( 'story_id', 'e' ) ) );
        // Content type
        $form->addElement ( new XoopsFormHidden ( 'story_type', $story_type ) );
        // Content title
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_FORMTITLE, 'story_title', 50, 255, $this->getVar ( 'story_title', 'e' ) ), true );
        // subtitle
        $form->addElement ( new XoopsFormText ( _NEWS_AM_STORY_SUBTITLE, 'story_subtitle', 50, 255, $this->getVar ( 'story_subtitle', 'e' ) ), false );
        // Topic
        $topic_Handler = xoops_getModuleHandler ( "topic", "news" );
        $perm_handler = NewsPermission::getHandler ();
        $topics = NewsPermission::News_PermissionItemId ( 'news_submit');
        $criteria = new CriteriaCompo ();
        global $xoopsUser;
        if ($xoopsUser) {
            if (! $xoopsUser->isAdmin ( 5 )) {
                $criteria->add ( new Criteria ( 'topic_id', '(' . implode ( ',', $topics ) . ')', 'IN' ) );
            }
        } else {
            $criteria->add ( new Criteria ( 'topic_id', '(' . implode ( ',', $topics ) . ')', 'IN' ) );
        }
        $topic = $topic_Handler->getObjects ( $criteria );
        if ($topic) {
            $tree = new XoopsObjectTree ( $topic, 'topic_id', 'topic_pid' );
            ob_start ();
            echo $tree->makeSelBox ( 'story_topic', 'topic_title', '--', $this->getVar ( 'story_topic', 'e' ), true );
            $topic_sel = new XoopsFormLabel ( _NEWS_AM_STORY_TOPIC, ob_get_contents () );
            $topic_sel->setDescription ( _NEWS_AM_STORY_TOPIC_DESC );
            $form->addElement ( $topic_sel );
            ob_end_clean ();
        } else {
            $form->addElement ( new XoopsFormHidden ( 'story_topic', 0 ) );
        }   
        // Short
        $Short_editor_tray = new XoopsFormElementTray ( _NEWS_AM_STORY_SHORT, '<br />' );
        if (class_exists ( 'XoopsFormEditor' )) {
            $configs = array ('name' => 'story_desc', 'value' => $this->getVar ( 'story_short', 'e' ), 'rows' => 25, 'cols' => 90, 'width' => '100%', 'height' => '400px', 'editor' => xoops_getModuleOption ( 'form_editor', 'news' ) );
            $Short_editor_tray->addElement ( new XoopsFormEditor ( '', 'story_short', $configs, false, $onfailure = 'textarea' ) );
        } else {
            $Short_editor_tray->addElement ( new XoopsFormDhtmlTextArea ( '', 'story_short', $this->getVar ( 'story_short', 'e' ), '100%', '100%' ) );
        }
        $form->addElement ($Short_editor_tray);
        // Text
        $text_editor_tray = new XoopsFormElementTray ( _NEWS_AM_STORY_FORMTEXT, '<br />' );
        if (class_exists ( 'XoopsFormEditor' )) {
            $configs = array ('name' => 'story_desc', 'value' => $this->getVar ( 'story_text', 'e' ), 'rows' => 25, 'cols' => 90, 'width' => '100%', 'height' => '400px', 'editor' => xoops_getModuleOption ( 'form_editor', 'news' ) );
            $text_editor_tray->addElement ( new XoopsFormEditor ( '', 'story_text', $configs, false, $onfailure = 'textarea' ) );
        } else {
            $text_editor_tray->addElement ( new XoopsFormDhtmlTextArea ( '', 'story_text', $this->getVar ( 'story_text', 'e' ), '100%', '100%' ) );
        }
        $text_editor_tray->setDescription ( _NEWS_AM_STORY_FORMTEXT_DESC );
        $form->addElement ($text_editor_tray);
        //tag
        if ((xoops_getModuleOption ( 'usetag', 'news' )) and (is_dir ( XOOPS_ROOT_PATH . '/modules/tag' ))) {
            $items_id = $this->isNew () ? 0 : $this->getVar ( "story_id" );
            include_once XOOPS_ROOT_PATH . "/modules/tag/include/formtag.php";
            $form->addElement ( new XoopsFormTag ( "item_tag", 60, 255, $items_id, $catid = 0 ) );
        }
        // options
        $options = new XoopsFormElementTray ( _NEWS_AM_STORY_OPTIONS, '<br />' );
        if (! NewsUtils::News_UtilityEditorHTML (  )) {
            if ($this->isNew ()) {
                $this->setVar ( 'dohtml', 0 );
                $this->setVar ( 'dobr', 1 );
            }
            // HTML
            $html_checkbox = new XoopsFormCheckBox ( '', 'dohtml', $this->getVar ( 'dohtml', 'e' ) );
            $html_checkbox->addOption ( 1, _NEWS_AM_STORY_DOHTML );
            $options->addElement ( $html_checkbox );
            // Break line
            $breaks_checkbox = new XoopsFormCheckBox ( '', 'dobr', $this->getVar ( 'dobr', 'e' ) );
            $breaks_checkbox->addOption ( 1, _NEWS_AM_STORY_BREAKS );
            $options->addElement ( $breaks_checkbox );
        } else {
            $form->addElement ( new xoopsFormHidden ( 'dohtml', 1 ) );
            $form->addElement ( new xoopsFormHidden ( 'dobr', 0 ) );
        }
        // Xoops Image
        $doimage_checkbox = new XoopsFormCheckBox ( '', 'doimage', $this->getVar ( 'doimage', 'e' ) );
        $doimage_checkbox->addOption ( 1, _NEWS_AM_STORY_DOIMAGE );
        $options->addElement ( $doimage_checkbox );
        // Xoops Code
        $xcodes_checkbox = new XoopsFormCheckBox ( '', 'doxcode', $this->getVar ( 'doxcode', 'e' ) );
        $xcodes_checkbox->addOption ( 1, _NEWS_AM_STORY_DOXCODE );
        $options->addElement ( $xcodes_checkbox );
        // Xoops Smiley
        $smiley_checkbox = new XoopsFormCheckBox ( '', 'dosmiley', $this->getVar ( 'dosmiley', 'e' ) );
        $smiley_checkbox->addOption ( 1, _NEWS_AM_STORY_DOSMILEY );
        $options->addElement ( $smiley_checkbox );
        // Editor and options
        $form->addElement ($options);
        // Image
        $story_img = $this->getVar ( 'story_img' ) ? $this->getVar ( 'story_img' ) : 'blank.gif';
        $uploadirectory_story_img = xoops_getModuleOption ( 'img_dir', 'news' ) . '/original';
        $fileseltray_story_img = new XoopsFormElementTray ( _NEWS_AM_GLOBAL_IMG, '<br />' );
        $fileseltray_story_img->addElement ( new XoopsFormLabel ( '', "<img class='fromimage' src='" . XOOPS_URL . $uploadirectory_story_img . $story_img . "' name='image_story_img' id='image_story_img' alt='' />" ) );
        $fileseltray_story_img->addElement ( new XoopsFormFile ( _NEWS_AM_GLOBAL_FORMUPLOAD, 'story_img', xoops_getModuleOption ( 'img_size', 'news' ) ), false );
        $form->addElement ( $fileseltray_story_img );
        // Files
        $uploadirectory_file = xoops_getModuleOption ( 'file_dir', 'news' );
        $fileseltray_file = new XoopsFormFile ( _NEWS_AM_FILE_SELECT, 'file_name', xoops_getModuleOption ( 'file_size', 'news' ) );
        $file = new XoopsFormElementTray ( _NEWS_AM_FILE );
        $file->addElement ( $fileseltray_file );
        $file->setDescription ( _NEWS_AM_STORY_FILE_DESC );
        $form->addElement ($file);
        // Submit buttons
        $button_tray = new XoopsFormElementTray ( '', '' );
        $submit_btn = new XoopsFormButton ( '', 'post', _SUBMIT, 'submit' );
        $button_tray->addElement ( $submit_btn );
        $cancel_btn = new XoopsFormButton ( '', 'cancel', _CANCEL, 'cancel' );
        $cancel_btn->setExtra ( 'onclick="javascript:history.go(-1);"' );
        $button_tray->addElement ( $cancel_btn );
        $form->addElement ( $button_tray );
        return $form;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     **/
    public function toArray() {
        $ret = array ();
        $vars = $this->getVars ();
        foreach ( array_keys ( $vars ) as $i ) {
            $ret [$i] = $this->getVar ( $i );
        }
        return $ret;
    }

}

/**
 * Content handler class
 *
 **/
class NewsStoryHandler extends XoopsPersistableObjectHandler {
    
    public function NewsStoryHandler($db) {
        parent::XoopsPersistableObjectHandler ( $db, 'news_story', 'news_story', 'story_id', 'story_alias' );
    }
    
    /**
     * Check if content alias already exist
     *
     * @param   String  $alias
     * @return  boolean
     **/
    public function News_StoryExistAlias($infos) {
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_alias', $infos['story_alias'] ) );
        if($infos['story_id']) {
            $criteria->add ( new Criteria ( 'story_id', $infos['story_id'] , '!='));
        }
        if ($this->getCount ( $criteria ) == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Get id from alias
     */
    public function News_StoryGetId($alias) {
        $criteria = new CriteriaCompo ();
        $criteria = new Criteria ( 'story_alias', $alias );
        $criteria->setLimit ( 1 );
        $obj_array = $this->getObjects ( $criteria, false, false );
        if (count ( $obj_array ) != 1) {
            return 0;
        }
        return $obj_array [0] [$this->keyName];
    }
    
    /**
     * Get Default content
     */
    public function News_StoryGetDefault($criteria = null) {
        $obj_array = $this->getObjects ( $criteria, false, false );
        if (count ( $obj_array ) != 1) {
            return 0;
        }
        return $obj_array [0] [$this->keyName];
    }
    
    /**
     * Get Default content
     */
    public function News_StoryDefault($default_info) {
        $storyDefault = array ();
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_default', 1 ) );
        $criteria->add ( new Criteria ( 'story_topic', $default_info ['id'] ) );
        $default = self::News_StoryGetDefault ( $criteria );
        $obj = self::get ( $default );
        $storyDefault = $obj->toArray ();
        $storyDefault ['story_publish'] = formatTimestamp ( $storyDefault ['story_publish'], _MEDIUMDATESTRING );
        $storyDefault ['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/' . $storyDefault ['story_img'];
        $storyDefault ['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/' . $storyDefault ['story_img'];
        $storyDefault ['topic'] = $default_info ['title'];
        $storyDefault ['topic_alias'] = $default_info ['alias'];
        $storyDefault ['url'] = NewsUtils::News_UtilityStoryUrl ( $storyDefault );
        if (isset ( $storyDefault ['story_id'] )) {
            return $storyDefault;
        }
    }
    
    /**
     * Get content list for admin side
     */
    public function News_StoryAdminList($story_infos) {
        $ret = array ();
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_status', $story_infos ['story_status'] ) );
        if ($story_infos ['story_static']) {
            $criteria->add ( new Criteria ( 'story_topic', '0', '>' ) );
        }
        $criteria->add ( new Criteria ( 'story_topic', $story_infos ['story_topic'] ) );
        $criteria->add ( new Criteria ( 'story_uid', $story_infos ['story_user'] ) );
        $criteria->setSort ( 'story_publish' );
        $criteria->setOrder ( 'DESC' );
        $criteria->setLimit ( $story_infos ['story_limit'] );
        $criteria->setStart ( $story_infos ['story_start'] );
        
        $obj = $this->getObjects ( $criteria, false );
        if ($obj) {
            foreach ( $obj as $root ) {
                $tab = array ();
                $tab = $root->toArray ();
                $tab ['owner'] = XoopsUser::getUnameFromId ( $root->getVar ( 'story_uid' ) );
                
            if(is_array($story_infos ['topics'])) {
                    foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                        $list [$i] ['topic_title'] = $story_infos ['topics'] [$i]->getVar ( "topic_title" );
                        $list [$i] ['topic_id'] = $story_infos ['topics'] [$i]->getVar ( "topic_id" );
                        $list [$i] ['topic_alias'] = $story_infos ['topics'] [$i]->getVar ( "topic_alias" );
                    }
                }
                if ($root->getVar ( 'story_topic' )) {
                    $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                    $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                }

                $tab ['url'] = NewsUtils::News_UtilityStoryUrl ($tab );
                $tab ['story_publish'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                $tab ['story_update'] = formatTimestamp ( $root->getVar ( 'story_update' ), _MEDIUMDATESTRING );
                $tab ['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/' . $root->getVar ( 'story_img' );
                $tab ['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/' . $root->getVar ( 'story_img' );
                $ret [] = $tab;
            }
        }
        return $ret;
    }
        
    /**
     * Get content list for user side
     */ 
    public function News_StoryList($story_infos) {
        $ret = array ();
        
        $access_topic = NewsPermission::News_PermissionItemId ( 'news_view');
        $topic_handler = xoops_getmodulehandler ( 'topic', 'news' );
        $topic_show = $topic_handler->News_TopicAllVisible($story_infos ['topics'],$story_infos ['story_topic']);
        if(isset($story_infos ['story_subtopic'])) {
            $topiclist = array_intersect($access_topic , $topic_show , $story_infos ['story_subtopic']);    
        } else {
            $topiclist = array_intersect($access_topic , $topic_show);
        }       

      $criteria = new CriteriaCompo ();
      $criteria->add ( new Criteria ( 'story_topic', '(' . implode ( ',', $topiclist ) . ')', 'IN' ) );
        $criteria->add ( new Criteria ( 'story_status', '1' ) );
      $criteria->add ( new Criteria ( 'story_uid', $story_infos ['story_user'] ) );
      $criteria->add ( new Criteria ( 'story_publish', 0 , '>' ));
        $criteria->add ( new Criteria ( 'story_publish', time() , '<=' ));
        $criteria->add ( new Criteria ( 'story_expire', 0 ));
        $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
    $criteria->setSort ( 'story_publish' );
        $criteria->setOrder ( 'DESC' );
        $criteria->setLimit ( $story_infos ['story_limit'] );
        $criteria->setStart ( $story_infos ['story_start'] );
        
        $obj = $this->getObjects ( $criteria, false );
        if ($obj) {
            foreach ( $obj as $root ) {
                $tab = array ();
                $tab = $root->toArray ();
                $tab ['owner'] = XoopsUser::getUnameFromId ( $root->getVar ( 'story_uid' ) );
                
            if(is_array($story_infos ['topics'])) {
                    foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                        $list [$i] ['topic_title'] = $story_infos ['topics'] [$i]->getVar ( "topic_title" );
                        $list [$i] ['topic_id'] = $story_infos ['topics'] [$i]->getVar ( "topic_id" );
                        $list [$i] ['topic_alias'] = $story_infos ['topics'] [$i]->getVar ( "topic_alias" );
                    }
                }
                if ($root->getVar ( 'story_topic' )) {
                    $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                    $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                }

                $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                $tab ['story_publish'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                $tab ['story_update'] = formatTimestamp ( $root->getVar ( 'story_update' ), _MEDIUMDATESTRING );
                $tab ['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/' . $root->getVar ( 'story_img' );
                $tab ['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/' . $root->getVar ( 'story_img' );
                
                if(!$root->getVar ( 'story_short' )) {
                    $tab ['story_short'] = mb_substr ( strip_tags($root->getVar ( 'story_text' )), 0, 500, 'utf-8' ) . "...";
                }
                    
                $ret [] = $tab;
            }
        }
        return $ret;
    }
    
    /**
     * Get content list for list block
     */
    public function News_StoryBlockList($story_infos ,$topics) {
        $ret = array ();

      $access_topic = NewsPermission::News_PermissionItemId ( 'news_view');
        
        if (! (count ( $topics ) == 1 && $topics [0] == 0)) {
            $topiclist = array_intersect($access_topic , $topics);
        } else {
           $topiclist = $access_topic;
        }   

      $criteria = new CriteriaCompo ();
      $criteria->add ( new Criteria ( 'story_topic', '(' . implode ( ',', $topiclist ) . ')', 'IN' ) );
        $criteria->add ( new Criteria ( 'story_status', '1' ) );
      $criteria->add ( new Criteria ( 'story_publish', $story_infos['story_publish'] , '>' ));
        $criteria->add ( new Criteria ( 'story_publish', time() , '<=' ));
        $criteria->add ( new Criteria ( 'story_expire', 0 ));
        $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
    $criteria->setSort ( $story_infos['story_sort'] );
        $criteria->setOrder ( 'DESC' );
        $criteria->setLimit ( $story_infos ['story_limit'] );
        
        $obj = $this->getObjects ( $criteria, false );
        if ($obj) {
            foreach ( $obj as $root ) {
                $tab = array ();
                $tab = $root->toArray ();
                $tab ['owner'] = XoopsUser::getUnameFromId ( $root->getVar ( 'story_uid' ) );
                
                foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                    $list [$i] ['topic_title'] = $story_infos ['topics'] [$i]->getVar ( "topic_title" );
                    $list [$i] ['topic_id'] = $story_infos ['topics'] [$i]->getVar ( "topic_id" );
                    $list [$i] ['topic_alias'] = $story_infos ['topics'] [$i]->getVar ( "topic_alias" );
                }
                if ($root->getVar ( 'story_topic' )) {
                    $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                    $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                }
                
                $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                $tab ['title'] = mb_strlen ( $root->getVar ( 'story_title' ), 'utf-8' ) > $story_infos ['lenght_title'] ? mb_substr ( $root->getVar ( 'story_title' ), 0, ($story_infos ['lenght_title']), 'utf-8' ) . "..." : $root->getVar ( 'story_title' );
                $tab ['date'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                
                if(!$root->getVar ( 'story_short' )) {
                    $tab ['story_short'] = mb_substr ( strip_tags($root->getVar ( 'story_text' )), 0, 300, 'utf-8' ) . "...";
                } else {
                    $tab ['story_short'] = strip_tags($root->getVar ( 'story_short' ));
                }   
                
                $ret [] = $tab;
            }
        }
        return $ret;
    }
    
    /**
     * Get Expire list for admin side
     */
    public function News_StoryExpireList($story_infos) {
        $ret = array ();
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_expire', 0 , '!='));
      $criteria->add ( new Criteria ( 'story_expire', time() , '<=' ));
        $criteria->setSort ( 'story_publish' );
        $criteria->setOrder ( 'DESC' );
        $criteria->setLimit ( $story_infos ['story_limit'] );
        $criteria->setStart ( $story_infos ['story_start'] );
        
        $obj = $this->getObjects ( $criteria, false );
        if ($obj) {
            foreach ( $obj as $root ) {
                $tab = array ();
                $tab = $root->toArray ();
                $tab ['owner'] = XoopsUser::getUnameFromId ( $root->getVar ( 'story_uid' ) );
                
            if(is_array($story_infos ['topics'])) {
                    foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                        $list [$i] ['topic_title'] = $story_infos ['topics'] [$i]->getVar ( "topic_title" );
                        $list [$i] ['topic_id'] = $story_infos ['topics'] [$i]->getVar ( "topic_id" );
                        $list [$i] ['topic_alias'] = $story_infos ['topics'] [$i]->getVar ( "topic_alias" );
                    }
                }
                if ($root->getVar ( 'story_topic' )) {
                    $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                    $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                    $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                }

                $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                $tab ['story_publish'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                $tab ['story_update'] = formatTimestamp ( $root->getVar ( 'story_update' ), _MEDIUMDATESTRING );
                $tab ['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/' . $root->getVar ( 'story_img' );
                $tab ['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/' . $root->getVar ( 'story_img' );
                $ret [] = $tab;
            }
        }
        return $ret;
    }
    
    /*
     *
     *
     */
    public function News_StoryJson($story_infos) {
        $ret = array ();
        $criteria = new CriteriaCompo ();
        if($story_infos['story_topic'] != 0) {
            $criteria->add ( new Criteria ( 'story_topic', $story_infos['story_topic'] ) );
        } else {
            $criteria->add ( new Criteria ( 'story_topic', 0 , '!=' ) );
        }
        if($story_infos['story_type'] == 'important') {
            $criteria->add ( new Criteria ( 'story_important', 1 ) );
        }
        if($story_infos['story_type'] == 'marquee') {
            $criteria->add ( new Criteria ( 'story_marquee', 1 ) );
        }
        if($story_infos['story_type'] == 'slide') {
            $criteria->add ( new Criteria ( 'story_slide', 1 ) );
        }
        if ($story_infos['story_start'] > 0) {
            $criteria->add ( new Criteria ( 'story_id', $story_infos['story_start'] , '<' ));
        }
        $criteria->add ( new Criteria ( 'story_status', '1' ) );
        $criteria->add ( new Criteria ( 'story_publish', 0 , '>' ));
        $criteria->add ( new Criteria ( 'story_publish', time() , '<=' ));
        $criteria->add ( new Criteria ( 'story_expire', 0 ));
        $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
        $criteria->setSort ( 'story_publish' );
        $criteria->setOrder ('DESC');
        $criteria->setLimit ( $story_infos ['story_limit'] );
        //$criteria->setStart ( $story_infos ['story_start'] );
        $obj = $this->getObjects ( $criteria, false );
        if ($obj) { 
            foreach ( $obj as $root ) {
                $tab = array();
                $tab = $root->toArray();
                $list = array();
                if(is_array($story_infos ['topics'])) {
                    foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                        $list[$i]['topic_title'] = $story_infos['topics'][$i]->getVar("topic_title");
                        //$list[$i]['topic_id'] = $story_infos['topics'][$i]->getVar("topic_id");
                    }
                }
                $json['story_id'] = $tab['story_id'];

                $text = $tab['story_title'];
                $text = strip_tags($text);
                $text = preg_replace("`\[.*\]`U", "", $text);
                $text = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $text);
                $text = htmlentities($text, ENT_COMPAT, 'utf-8');
                $text = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $text);
                $text = stripslashes($text);
                $json['story_title'] = $text;

                $json['story_alias'] = $tab['story_alias'];
                $json['story_publish'] = $tab['story_publish'];
                $json['story_topic'] = $tab['story_topic'];
                $json['story_topic_title'] = $list[$tab['story_topic']]['topic_title'];
                $json['story_img'] = $tab['story_img'];
                $json['story_hits'] = $tab['story_hits'];

                unset($tab);
                $ret[] = $json;
            }
        }
        return json_encode($ret);
    } 
    
    /**
     * Get Content Count for user side
     * use in homepage public function in NewsUtils class
     */
    public function News_StoryCount($story_infos) {
        $access_topic = NewsPermission::News_PermissionItemId ( 'news_view');
        $topic_handler = xoops_getmodulehandler ( 'topic', 'news' );
        $topic_show = $topic_handler->News_TopicAllVisible($story_infos ['topics'],$story_infos ['story_topic']);
        if(isset($story_infos ['story_subtopic'])) {
            $topiclist = array_intersect($access_topic , $topic_show , $story_infos ['story_subtopic']);    
        } else {
            $topiclist = array_intersect($access_topic , $topic_show);
        }       

      $criteria = new CriteriaCompo ();
      $criteria->add ( new Criteria ( 'story_topic', '(' . implode ( ',', $topiclist ) . ')', 'IN' ) );
        $criteria->add ( new Criteria ( 'story_status', $story_infos ['story_status'] ) );
      $criteria->add ( new Criteria ( 'story_uid', $story_infos ['story_user'] ) );
      $criteria->add ( new Criteria ( 'story_publish', 0 , '>' ));
        $criteria->add ( new Criteria ( 'story_publish', time() , '<=' ));
        $criteria->add ( new Criteria ( 'story_expire', 0 ));
        $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
    $criteria->setSort ( 'story_publish' );
        $criteria->setOrder ( 'DESC' );
        $criteria->setLimit ( $story_infos ['story_limit'] );
        $criteria->setStart ( $story_infos ['story_start'] );
        return $this->getCount ( $criteria );
    }
    
    /**
     * Get Content Count for admin side
     */
    public function News_StoryAdminCount($story_infos) {
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_topic', $story_infos ['story_topic'] ) );
        if ($story_infos ['story_static']) {
            $criteria->add ( new Criteria ( 'story_topic', '0', '>' ) );
        }
        return $this->getCount ( $criteria );
    }
        
    /**
     * Get Offline Content Count for admin side
     */ 
    public function News_StoryOfflineCount($story_infos) {
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_topic', $story_infos ['story_topic'] ) );
        $criteria->add ( new Criteria ( 'story_status', 0 ) );
        return $this->getCount ( $criteria );
    }
    
    /**
     * Get Expire Content Count for admin side
     */
    public function News_StoryExpireCount ($story_infos) {
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_topic', $story_infos ['story_topic'] ) );
      $criteria->add ( new Criteria ( 'story_expire', 0 , '!='));
      $criteria->add ( new Criteria ( 'story_expire', time() , '<=' ));
        return $this->getCount ( $criteria );
    }
    /**
     * Get All Content Count for admin side
     */
    public function News_StoryAllCount() {
        $criteria = new CriteriaCompo ();
      return $this->getCount ( $criteria );
    }
    
    /**
     * Get Last Content
     */
    public function News_StoryGetLast($story_infos) {
        $ret = array ();
        $criteria = new CriteriaCompo ();
        $obj = $this->getObjects ( $criteria, false );
        if ($obj) {
            foreach ( $obj as $root ) {
                $tab = array ();
                $tab = $root->toArray ();
                $tab ['topic'] = NewsTopicHandler::News_TopicFromId ( $root->getVar ( 'story_topic' ) );
            $tab ['topic_alias'] = $tab ['topic'];
                $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                $ret [] = $tab;
            }
        }
        return $ret;
    }

    /**
     *
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Hervé Thouzard (http://www.instant-zero.com)
     */
    
    public function News_StoryUpdateHits($story_id) {
        $sql = 'UPDATE ' . $this->table . ' SET story_hits = story_hits + 1 WHERE story_id= ' . intval ( $story_id );
        return $this->db->queryF ( $sql );
    }
    
    /**
     *
     * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
     * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
     * @author      Zoullou (http://www.zoullou.net)
     */
    public function News_StorySearch($queryArray, $condition, $limit, $start, $userId) {
        $ret = array ();
        include_once 'topic.php';
        $criteria = new CriteriaCompo ();
        if ($userId > 0)
        $criteria->add ( new Criteria ( 'story_uid', $userId ) );
        $criteria->add ( new Criteria ( 'story_status', 1 ) );
        if (is_array ( $queryArray ) && count ( $queryArray ) > 0) {
            $subCriteria = new CriteriaCompo ();
            foreach ( $queryArray as $keyWord ) {
                $keyWordCriteria = new CriteriaCompo ();
                $keyWordCriteria->add ( new Criteria ( 'story_title', '%' . $keyWord . '%', 'LIKE' ) );
                $keyWordCriteria->add ( new Criteria ( 'story_text', '%' . $keyWord . '%', 'LIKE' ), 'OR' );
                $keyWordCriteria->add ( new Criteria ( 'story_subtitle', '%' . $keyWord . '%', 'LIKE' ), 'OR' );
                $keyWordCriteria->add ( new Criteria ( 'story_short', '%' . $keyWord . '%', 'LIKE' ), 'OR' );
                $keyWordCriteria->add ( new Criteria ( 'story_words', '%' . $keyWord . '%', 'LIKE' ), 'OR' );
                $keyWordCriteria->add ( new Criteria ( 'story_desc', '%' . $keyWord . '%', 'LIKE' ), 'OR' );
                $subCriteria->add ( $keyWordCriteria, $condition );
                unset ( $keyWordCriteria );
            }
            $criteria->add ( $subCriteria );
        }
        $criteria->setStart ( $start );
        $criteria->setLimit ( $limit );
        $criteria->setSort ( 'story_publish' );
$criteria->setOrder ( 'DESC' );
        
        $stores = $this->getObjects ( $criteria );
        
        $ret = array ();
        foreach ( $stores as $story ) {
            $data = array ();
            $data = $story->toArray ();
            $data ['image'] = 'images/forum.gif';
            $data ['topic'] = NewsTopicHandler::News_TopicFromId ( $story->getVar ( 'story_topic' ) );
            $data ['topic_alias'] = $data ['topic'];
            $data ['link'] = XOOPS_URL . '/modules/news/article.php?storyid=' . $story->getVar ( 'story_id' );
            $data ['title'] = $story->getVar ( 'story_title' );
            $data ['time'] = $story->getVar ( 'story_publish' );
            $data ['uid'] = $story->getVar ( 'story_uid' );
            $ret [] = $data;
        }
        
        return $ret;
    }
    
    /**
     * Generate public function for update user post
     *
     * @ Update user post count after send approve content
     * @ Update user post count after change status content
     * @ Update user post count after delete content
     */
    public function News_StoryUpdatePost($story_uid, $story_status, $story_action) {
        switch ($story_action) {
            case 'add' :
                if ($story_uid && $story_status) {
                    $user = new xoopsUser ( $story_uid );
                    $member_handler = & xoops_gethandler ( 'member' );
                    $member_handler->updateUserByField ( $user, 'posts', $user->getVar ( 'posts' ) + 1 );
                }
                break;
            
            case 'delete' :
                if ($story_uid && $story_status) {
                    $user = new xoopsUser ( $story_uid );
                    $member_handler = & xoops_gethandler ( 'member' );
                    $member_handler->updateUserByField ( $user, 'posts', $user->getVar ( 'posts' ) - 1 );
                }
                break;
            
            case 'status' :
                if ($story_uid) {
                    $user = new xoopsUser ( $story_uid );
                    $member_handler = & xoops_gethandler ( 'member' );
                    if ($story_status) {
                        $member_handler->updateUserByField ( $user, 'posts', $user->getVar ( 'posts' ) - 1 );
                    } else {
                        $member_handler->updateUserByField ( $user, 'posts', $user->getVar ( 'posts' ) + 1 );
                    }
                }
                break;
        }
    }
    
    /**
     * Set number of files for each content
     */
    public function News_StoryFile($action , $id , $previous = null) {
        switch($action) {
            case 'add':
                $sql = 'UPDATE ' . $this->table . ' SET story_file = story_file + 1 WHERE story_id= ' . intval ( $id );
               break;
            
            case 'delete':
                $sql = 'UPDATE ' . $this->table . ' SET story_file = story_file - 1 WHERE story_id= ' . intval ( $id );
               break;
        }
        return $this->db->queryF ( $sql );  
    }   
    
    /**
     * Get all files fro each content
     */
    public function News_StoryGetFile() {
        $criteria = new CriteriaCompo ();
        $criteria->add ( new Criteria ( 'story_file', '0', '>' ) );
        return $this->getAll ( $criteria );
    }   
    
    /**
     * Get related contents
     */
     public function News_StoryRelated($story_infos) {
         $ret = array();
         $criteria = new CriteriaCompo ();
         $criteria->add ( new Criteria ( 'story_topic', $story_infos ['story_topic'] ) );
         $criteria->add ( new Criteria ( 'story_status', 1 ) );
         $criteria->add ( new Criteria ( 'story_publish', time() , '<=' ));
         $criteria->add ( new Criteria ( 'story_publish', 0 , '>' ));
         $criteria->add ( new Criteria ( 'story_expire', time() , '>=' ));
         $criteria->add ( new Criteria ( 'story_expire', 0 ) ,'OR');
         $criteria->add ( new Criteria ( 'story_id', $story_infos ['story_id'], '!=' ) );
         $criteria->setSort ( 'story_publish' );
         $criteria->setOrder ( 'DESC' );
         $criteria->setLimit ( $story_infos ['story_limit'] );
         $obj = $this->getObjects ( $criteria, false );
         if ($obj) {
             foreach ( $obj as $root ) {
                 $tab = array ();
                 $tab = $root->toArray ();
                 $tab ['topic_alias'] = $story_infos ['topic_alias'];
                 $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                 $ret [] = $tab;
             }
         }
         return $ret;
     }  

    /**
     * Get Spotlight Id
     */
     public function News_StorySpotlightId($list) {
         $defaultid = array();
         $storyid = array();
         
         foreach ( $list as $item ) {
             $storyid [] = $item['story_id'];
           if($item['story_default'] === '1') {
             $defaultid [] = $item['story_id']; 
           }    
         }  
       
       $id = array();

       if($defaultid) {
        $id['spotlightid'] = max($defaultid);
        unset($defaultid['0']);
       }    else {
        $id['spotlightid'] = max($storyid);
        unset($storyid['0']);
       }    

       if($defaultid) {
        $id['subspotlightid1'] = max($defaultid);
        unset($defaultid['0']);
       } else {
        $id['subspotlightid1'] = max($storyid);
        unset($storyid['0']);
       }    

       if($defaultid) {
        $id['subspotlightid2'] = max($defaultid);
        unset($defaultid['0']);
       } else {
        $id['subspotlightid2'] = max($storyid);
        unset($storyid['0']);
       }    

         return $id;
     }      
     
  /**
    * Returns the number of published news per topic
    * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
    * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
    * @author      Hervé Thouzard (http://www.instant-zero.com)
    */
     public function News_StoryCountByTopic()
     {
         $ret = array();
         $sql = "SELECT count( story_id ) AS cpt, story_topic FROM ".$this->db->prefix('news_story')." WHERE ( story_publish > 0 AND story_publish <= " . time() . " ) AND ( story_expire = 0 OR story_expire > " . time() . " ) GROUP BY story_topic";
         $result = $this->db->query($sql);
         while ($row = $this->db->fetchArray($result)) {
             $ret [ $row["story_topic"] ] = $row["cpt"];
         }
         return $ret;
     }
     
  /**
    * Get archive month
    * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
    * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
    * @author      Hervé Thouzard (http://www.instant-zero.com)
    */
     public function News_StoryArchiveMonth() {
         $sql = "SELECT `story_publish` FROM ".$this->db->prefix('news_story')." WHERE ( story_status = 1 ) AND ( story_topic != 0  ) AND ( story_publish > 0 AND story_publish <= " . time() . " ) AND ( story_expire = 0 OR story_expire <= " . time() . " ) ORDER BY story_publish DESC";
        $result = $this->db->query($sql);
        return $result;
     }  
     
  /**
    * Get archive
    */
     public function News_StoryArchive($publish_start, $publish_end ,$topics ,$limit ,$start) {
         $ret = array();
         $criteria = new CriteriaCompo ();
         $criteria->add ( new Criteria ( 'story_status', '1' ) );
       $criteria->add ( new Criteria ( 'story_publish', $publish_start , '>' ));
         $criteria->add ( new Criteria ( 'story_publish', $publish_end , '<=' ));
         $criteria->add ( new Criteria ( 'story_expire', 0 ));
         $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
         $criteria->setSort ( 'story_publish' );
         $criteria->setOrder ( 'DESC' );
         $criteria->setLimit ( $limit );
         $criteria->setStart ( $start );
         $obj = $this->getObjects ( $criteria, false );
         if ($obj) {
             foreach ( $obj as $root ) {
                 $tab = array ();
                 $tab = $root->toArray ();
                 foreach ( array_keys ( $topics ) as $i ) {
                     $list [$i] ['topic_title'] = $topics [$i]->getVar ( "topic_title" );
                     $list [$i] ['topic_id'] = $topics [$i]->getVar ( "topic_id" );
                     $list [$i] ['topic_alias'] =$topics [$i]->getVar ( "topic_alias" );
                 }
                 $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                 $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                 $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                 $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                 $tab ['story_publish'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                 $tab ['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/' . $root->getVar ( 'story_img' );
                 $tab ['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/' . $root->getVar ( 'story_img' );
                 $ret [] = $tab;
             }
         }
         return $ret;
     }  
     
  /**
    * Get archive count
    */
     public function News_StoryArchiveCount($publish_start, $publish_end ,$topics) {
         $criteria = new CriteriaCompo ();
         $criteria->add ( new Criteria ( 'story_status', '1' ) );
       $criteria->add ( new Criteria ( 'story_publish', $publish_start , '>' ));
         $criteria->add ( new Criteria ( 'story_publish', $publish_end , '<=' ));
         $criteria->add ( new Criteria ( 'story_expire', 0 ));
         $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
         return $this->getCount ( $criteria );
     }  
     
  /**
    * News Prune Count
    */
    public function News_StoryPruneCount($timestamp,$expired,$topiclist) {
         $criteria = new CriteriaCompo ();
         $criteria->add ( new Criteria ( 'story_publish', $timestamp , '<=' ));
         if($expired) {
             $criteria->add ( new Criteria ( 'story_expire', 0 ));
            $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
         }
         if($topiclist) {
             $criteria->add ( new Criteria ( 'story_topic', '(' . $topiclist . ')', 'IN' ) );   
         }  
         return $this->getCount ( $criteria );
    }   
    
    /**
    * News Delete Before Date
    */
    public function News_StoryDeleteBeforeDate($timestamp,$expired,$topiclist) {
         $criteria = new CriteriaCompo ();
         $criteria->add ( new Criteria ( 'story_publish', $timestamp , '<=' ));
         if($expired) {
             $criteria->add ( new Criteria ( 'story_expire', 0 ));
            $criteria->add ( new Criteria ( 'story_expire', time() , '>' ) ,'OR');
         }
         if($topiclist) {
             $criteria->add ( new Criteria ( 'story_topic', '(' . $topiclist . ')', 'IN' ) );   
         }  
         /*
         $obj = $this->getObjects ( $criteria, false );
         if ($obj) {
             foreach ( $obj as $root ) {
             xoops_comment_delete( $root->getVar ( 'story_id' ));
                //xoops_notification_deletebyitem( 'story', $root->getVar ( 'story_id' ));
             }
         }
         */
         $this->deleteAll($criteria);
         return true;
    }   
    
    public function News_StorySlide($story_infos ,$topics) {
         $ret = array();
       $access_topic = NewsPermission::News_PermissionItemId ( 'news_view');
         if (! (count ( $topics ) == 1 && $topics [0] == 0)) {
             $topiclist = array_intersect($access_topic , $topics);
         } else {
            $topiclist = $access_topic;
         }  
       
       $criteria = new CriteriaCompo ();

       $part1 = new CriteriaCompo ();
       $part1->add ( new Criteria ( 'story_topic', '(' . implode ( ',', $topiclist ) . ')', 'IN' ));    
       $criteria->add($part1);
       
       $part2 = new CriteriaCompo ();
       $part2->add ( new Criteria ( 'story_status', '1' ));
       $criteria->add($part2);
       
       $q3 = new CriteriaCompo ();
       $q3->add ( new Criteria ( 'story_slide', '1' ));
       $criteria->add($q3);
 
       $part4 = new CriteriaCompo ();
         $part4->add ( new Criteria ( 'story_publish', time() , '<=' ));
         $part4->add ( new Criteria ( 'story_publish', 0 , '>' ));
         $criteria->add($part4);
         
         $part5 = new CriteriaCompo ();
         $part5->add ( new Criteria ( 'story_expire', time() , '>=' ));
         $part5->add ( new Criteria ( 'story_expire', 0 ) ,'OR');
         $criteria->add($part5);
         
         $criteria->setSort ( 'story_publish' );
         $criteria->setOrder ( 'DESC' );
         $criteria->setLimit ( $story_infos ['story_limit'] );
         
         $obj = $this->getObjects ( $criteria, false );
         if ($obj) {
            foreach ( $obj as $root ) {
                 $tab = array ();
                 $tab = $root->toArray ();
                
                 foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                     $list [$i] ['topic_title'] = $story_infos ['topics'] [$i]->getVar ( "topic_title" );
                     $list [$i] ['topic_id'] = $story_infos ['topics'] [$i]->getVar ( "topic_id" );
                     $list [$i] ['topic_alias'] = $story_infos ['topics'] [$i]->getVar ( "topic_alias" );
                 }
                 if ($root->getVar ( 'story_topic' )) {
                     $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                     $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                     $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                 }
                
                 $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                 $tab ['story_publish'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                 $tab ['imageurl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/medium/' . $root->getVar ( 'story_img' );
                 $tab ['thumburl'] = XOOPS_URL . xoops_getModuleOption ( 'img_dir', 'news' ) . '/thumb/' . $root->getVar ( 'story_img' );
                 $tab ['story_title'] = mb_substr ( strip_tags($root->getVar ( 'story_title' )), 0, $story_infos['title_lenght'], 'utf-8' );
                 
                 if(!$root->getVar ( 'story_short' )) {
                     $tab ['story_short'] = mb_substr ( strip_tags($root->getVar ( 'story_text' )), 0, $story_infos['desc_lenght'], 'utf-8' ) . "...";
                 } else {
                     $tab ['story_short'] = mb_substr ( strip_tags($root->getVar ( 'story_short' )), 0, $story_infos['desc_lenght'], 'utf-8' ) . "...";
                 }  
                
                 $ret [] = $tab;
             }
         }
         return $ret;   
    }
    
    public function News_StoryMarquee($story_infos ,$topics) {
         $ret = array();
       $access_topic = NewsPermission::News_PermissionItemId ( 'news_view');
         if (! (count ( $topics ) == 1 && $topics [0] == 0)) {
             $topiclist = array_intersect($access_topic , $topics);
         } else {
            $topiclist = $access_topic;
         }  
       
       $criteria = new CriteriaCompo ();

       $part1 = new CriteriaCompo ();
       $part1->add ( new Criteria ( 'story_topic', '(' . implode ( ',', $topiclist ) . ')', 'IN' ));    
       $criteria->add($part1);
       
       $part2 = new CriteriaCompo ();
       $part2->add ( new Criteria ( 'story_status', '1' ));
       $criteria->add($part2);
       
       $q3 = new CriteriaCompo ();
       $q3->add ( new Criteria ( 'story_marquee', '1' ));
       $criteria->add($q3);
 
       $part4 = new CriteriaCompo ();
         $part4->add ( new Criteria ( 'story_publish', time() , '<=' ));
         $part4->add ( new Criteria ( 'story_publish', 0 , '>' ));
         $criteria->add($part4);
         
         $part5 = new CriteriaCompo ();
         $part5->add ( new Criteria ( 'story_expire', time() , '>=' ));
         $part5->add ( new Criteria ( 'story_expire', 0 ) ,'OR');
         $criteria->add($part5);
         
         $criteria->setSort ( 'story_publish' );
         $criteria->setOrder ( 'DESC' );
         $criteria->setLimit ( $story_infos ['story_limit'] );
         
         $obj = $this->getObjects ( $criteria, false );
         if ($obj) {
            foreach ( $obj as $root ) {
                 $tab = array ();
                 $tab = $root->toArray ();
                
                 foreach ( array_keys ( $story_infos ['topics'] ) as $i ) {
                     $list [$i] ['topic_title'] = $story_infos ['topics'] [$i]->getVar ( "topic_title" );
                     $list [$i] ['topic_id'] = $story_infos ['topics'] [$i]->getVar ( "topic_id" );
                     $list [$i] ['topic_alias'] = $story_infos ['topics'] [$i]->getVar ( "topic_alias" );
                 }
                 if ($root->getVar ( 'story_topic' )) {
                     $tab ['topic'] = $list [$root->getVar ( 'story_topic' )] ['topic_title'];
                     $tab ['topic_alias'] = $list [$root->getVar ( 'story_topic' )] ['topic_alias'];
                     $tab ['topicurl'] = NewsUtils::News_UtilityTopicUrl ( array('topic_id'=>$list [$root->getVar ( 'story_topic' )] ['topic_id'], 'topic_alias'=>$list [$root->getVar ( 'story_topic' )] ['topic_alias'] ));
                 }
                
                 $tab ['url'] = NewsUtils::News_UtilityStoryUrl ( $tab );
                 $tab ['story_publish'] = formatTimestamp ( $root->getVar ( 'story_publish' ), _MEDIUMDATESTRING );
                 $tab ['story_title'] = mb_substr ( strip_tags($root->getVar ( 'story_title' )), 0, $story_infos['title_lenght'], 'utf-8' );
                 $ret [] = $tab;
             }
         }
         return $ret;   
    }       
    
    public function News_StoryUpdateRating($id, $rating, $votes)
    {
        $sql = 'UPDATE ' . $this->db->prefix('news_story') . ' SET story_rating = ' . intval($rating) . ', story_votes = ' . intval($votes) . ' WHERE story_id = ' . intval($id);
        return $this->db->queryF($sql);
    }
}

?>