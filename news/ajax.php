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
$op = NewsUtils::News_UtilityCleanVars ( $_REQUEST, 'op', '', 'string' );

if(!empty($op)) {
	switch($op) {
		// Get last story as json
		case 'story':
		   $story_infos =  array();
		   $story_infos['story_id'] = NewsUtils::News_UtilityCleanVars ( $_REQUEST, 'storyid', 0, 'int' );
		   $story_infos['story_topic'] = NewsUtils::News_UtilityCleanVars ( $_REQUEST, 'storytopic', 0, 'int' );
		   $story_infos['story_limit'] = NewsUtils::News_UtilityCleanVars ( $_REQUEST, 'limit', 50, 'int' );
		   $return = $story_handler->News_StoryJson($story_infos);
			break;
		// vote to story	
		case 'rate':
			if(xoops_getModuleOption('vote_active', 'news')) {
				$info = array();
				$info['story'] = NewsUtils::News_UtilityCleanVars ( $_POST, 'story', 0, 'int' );
				$info['rate'] = NewsUtils::News_UtilityCleanVars ( $_POST, 'rate', 0, 'int' );
				if($info['story'] && $info['rate']) {
					$return = $rate_handler->News_RateDo($info);
				}	
			}
			break;	
	}
	echo $return;	
}
?>