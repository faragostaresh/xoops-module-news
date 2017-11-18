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
 * News page class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die ("XOOPS root path not defined");
}

class NewsPermission
{

    public static function getHandler()
    {
        static $permHandler;
        if (!isset ($permHandler)) {
            $permHandler = new NewsPermission ();
        }
        return $permHandler;
    }

    public static function NewsPermissionUserGroup($user)
    {
        if (is_a($user, 'XoopsUser')) {
            return $user->getGroups();
        } else {
            return XOOPS_GROUP_ANONYMOUS;
        }
    }

    public static function NewsPermissionAuthorizedTopic($user, $perm)
    {
        static $authorizedCat;
        $userId = ($user) ? $user->getVar('uid') : 0;
        if (!isset ($authorizedCat [$perm] [$userId])) {
            $groupPermHandler = xoops_gethandler('groupperm');
            $moduleHandler = xoops_gethandler('module');
            $module = $moduleHandler->getByDirname('news');
            $authorizedCat [$perm] [$userId] = $groupPermHandler->getItemIds($perm, self::NewsPermissionUserGroup($user), $module->getVar("mid"));
        }
        return $authorizedCat [$perm] [$userId];
    }

    public static function NewsPermissionIsAllowed($user, $perm, $topic_id)
    {
        $autorizedCat = self::NewsPermissionAuthorizedTopic($user, $perm);
        return in_array($topic_id, $autorizedCat);
    }

    public static function NewsPermissionSet($gperm_name, $groups_action, $id, $new)
    {
        global $xoopsModule;
        $gperm_handler = xoops_gethandler('groupperm');
        if (!$new) {
            $criteria = new CriteriaCompo ();
            $criteria->add(new Criteria ('gperm_itemid', $id));
            $criteria->add(new Criteria ('gperm_modid', $xoopsModule->getVar('mid')));
            $criteria->add(new Criteria ('gperm_name', $gperm_name));
            $gperm_handler->deleteAll($criteria);
        }

        if (isset ($groups_action)) {
            foreach ($groups_action as $onegroup_id) {
                $gperm_handler->addRight($gperm_name, $id, $onegroup_id, $xoopsModule->getVar('mid'));
            }
        }

    }

    public static function NewsPermissionItemId($permtype)
    {
        global $xoopsUser;
        $moduleHandler = xoops_gethandler('module');
        $module = $moduleHandler->getByDirname('news');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gperm_handler = xoops_gethandler('groupperm');
        $categories = $gperm_handler->getItemIds($permtype, $groups, $module->getVar("mid"));
        return $categories;
    }

}

?>