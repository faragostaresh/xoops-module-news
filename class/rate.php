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
class news_rate extends XoopsObject
{

    public $db;
    public $table;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->initVar("rate_id", XOBJ_DTYPE_INT, '');
        $this->initVar("rate_story", XOBJ_DTYPE_INT, '');
        $this->initVar("rate_user", XOBJ_DTYPE_INT, '');
        $this->initVar("rate_rating", XOBJ_DTYPE_INT, '');
        $this->initVar("rate_hostname", XOBJ_DTYPE_TXTBOX, '');
        $this->initVar("rate_created", XOBJ_DTYPE_INT, '');

        $this->db = $GLOBALS ['xoopsDB'];
        $this->table = $this->db->prefix('news_rate');
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     **/
    public function toArray()
    {
        $ret = [];
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret [$i] = $this->getVar($i);
        }
        return $ret;
    }

}

class NewsRateHandler extends XoopsPersistableObjectHandler
{

    public function __construct($db)
    {
        parent::__construct($db, 'news_rate', 'news_rate', 'rate_id', 'rate_story');
    }

    /**
     * Do rate
     *
     * @param   Array $info
     * @return  boolean
     **/
    public function NewsRateDo($info)
    {
        $ret = [];
        if (in_array($info['rate'], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])) {
            // Get and check user ID
            $info['user'] = NewsUtils::NewsUtilityCurrentUserID();
            if ($info['user']) {
                // Check voted to this story or not
                if (!self::NewsRateCheckUser($info)) {
                    $obj = $this->create();
                    $obj->setVar('rate_story', $info['story']);
                    $obj->setVar('rate_user', $info['user']);
                    $obj->setVar('rate_rating', $info['rate']);
                    $obj->setVar('rate_hostname', getenv("REMOTE_ADDR"));
                    $obj->setVar('rate_created', time());
                    if ($this->insert($obj)) {
                        self::NewsRateMake($info);
                        $ret['status'] = 1;
                        $ret['message'] = _NEWS_MD_RATE_MESSAGE_SAVE;
                    } else {
                        $ret['status'] = 0;
                        $ret['message'] = _NEWS_MD_RATE_MESSAGE_NOTSAVE;
                    }
                } else {
                    $ret['status'] = 0;
                    $ret['message'] = _NEWS_MD_RATE_MESSAGE_VOTEBEFORE;
                }
            } else {
                $ret['status'] = 0;
                $ret['message'] = _NEWS_MD_RATE_MESSAGE_NOTUSER;
            }
        } else {
            $ret['status'] = 0;
            $ret['message'] = _NEWS_MD_RATE_MESSAGE_OUTOFRANGE;
        }
        return $ret;
    }

    /**
     * Check user
     *
     * @param   String $alias
     * @return  boolean
     **/
    public function NewsRateCheckUser($info)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('rate_story', $info['story']));
        $criteria->add(new Criteria('rate_user', $info['user']));
        return $this->getCount($criteria);
    }

    public function NewsRateMake($info)
    {

        $sql = 'SELECT rate_rating FROM ' . $this->table . ' WHERE rate_story = ' . $info['story'];
        $voteresult = $this->db->queryF($sql);
        $votes = $this->db->getRowsNum($voteresult);
        $totalrating = 0;
        while (list($rating) = $this->db->fetchRow($voteresult)) {
            $totalrating += $rating;
        }
        $finalrating = $totalrating / $votes;
        $finalrating = number_format($finalrating, 4);
        //
        $story_handler = xoops_getModuleHandler("story", "news");
        $story_handler->NewsStoryUpdateRating($info['story'], $finalrating, $votes);


    }
}

?>