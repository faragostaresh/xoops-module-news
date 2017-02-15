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
 * News language file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Hossein Azizabadi (AKA Voltan)
 * @version     $Id$
 */

// Global page
define('_NEWS_AM_GLOBAL_ADD_CONTENT', 'إنشاء الصفحة');
define('_NEWS_AM_GLOBAL_ADD_TOPIC', 'أنشاء الفئة');
define('_NEWS_AM_GLOBAL_ADD_FILE', 'إنشاء الملف');
define('_NEWS_AM_GLOBAL_IMG', 'الصورة');
define('_NEWS_AM_GLOBAL_FORMUPLOAD', 'إختیار الصورة');
// Index page
define("_NEWS_AM_INDEX_ADMENU1", "الفئات");
define("_NEWS_AM_INDEX_ADMENU2", "الصفحات");
define("_NEWS_AM_INDEX_TOPICS", "<span class='green'>%s</span> تقع الفئة في قاعدة البیانات");
define("_NEWS_AM_INDEX_CONTENTS", "<span class='green'>%s</span> تقع الصفحة في قاعدة البیانات ");
define("_NEWS_AM_INDEX_CONTENTS_OFFLINE", "There are <span class='red'>%s</span> Offline news in our database");
define("_NEWS_AM_INDEX_CONTENTS_EXPIRE", "There are <span class='red'>%s</span> Expire news in our database");
// Topic page
define('_NEWS_AM_TOPIC_FORM', 'إدارة الفئات');
define('_NEWS_AM_TOPIC_ID', 'ID');
define('_NEWS_AM_TOPIC_NUM', 'وزن');
define('_NEWS_AM_TOPIC_NAME', 'العنوان');
define('_NEWS_AM_TOPIC_PARENT', 'الفئة الرئیسیة');
define('_NEWS_AM_TOPIC_DESC', 'الوصف');
define('_NEWS_AM_TOPIC_IMG', 'الصورة');
define('_NEWS_AM_TOPIC_WEIGHT', 'العرض');
define('_NEWS_AM_TOPIC_SHOWTYPE', 'طریقة العرض');
define('_NEWS_AM_TOPIC_SHOWTYPE_DESC', 'إذا كنت تريد استخدام الإعدادات التالية.<br /> یجب تغییر <b>طریقة العرض</b> من وحدة القاعدة <br />الی خیارات أخری ها.');
define('_NEWS_AM_TOPIC_PERPAGE', 'کل الصفحة');
define('_NEWS_AM_TOPIC_COLUMNS', 'عمود');
define('_NEWS_AM_TOPIC_ONLINE', 'نشیط');
define('_NEWS_AM_TOPIC_MENU', 'القائمة');
define('_NEWS_AM_TOPIC_SHOW', 'العرض');
define('_NEWS_AM_TOPIC_ACTION', 'نشیط');
define('_NEWS_AM_TOPIC_PID', 'والد');
define('_NEWS_AM_TOPIC_DATE_CREATED', 'ساعة الإنشاء');
define('_NEWS_AM_TOPIC_DATE_UPDATE', 'ساعة التحدیث');
define('_NEWS_AM_TOPIC_SHOWTOPIC', 'عرض الفئة');
define('_NEWS_AM_TOPIC_SHOWSUB', 'Display SubTopic list');
define('_NEWS_AM_TOPIC_SHOWAUTHOR', 'عرض المحرر');
define('_NEWS_AM_TOPIC_SHOWDATE', 'عرض التاریخ');
define('_NEWS_AM_TOPIC_SHOWDPF', 'عرض PDF');
define('_NEWS_AM_TOPIC_SHOWPRINT', 'عرض الطباعة');
define('_NEWS_AM_TOPIC_SHOWMAIL', 'عرض أخبار الأصدقاء');
define('_NEWS_AM_TOPIC_SHOWNAV', 'نمایش ناوبری');
define('_NEWS_AM_TOPIC_SHOWHITS', 'عرض الزایارات');
define('_NEWS_AM_TOPIC_SHOWCOMS', 'عرض التعلیقات المنشورة ');
define('_NEWS_AM_TOPIC_HOMEPAGE', 'خیارات الصفحة الأولی للفئة');
define('_NEWS_AM_TOPIC_HOMEPAGE_DESC', 'Seting content show type in topic pages');
define('_NEWS_AM_TOPIC_HOMEPAGE_1', 'List all contents from this topic and subtopics');
define('_NEWS_AM_TOPIC_HOMEPAGE_2', 'List all subtopics');
define('_NEWS_AM_TOPIC_HOMEPAGE_3', 'List all contents from just this topic');
define('_NEWS_AM_TOPIC_HOMEPAGE_4', 'Show selected content from this topic');
define('_NEWS_AM_TOPIC_OPTIONS', 'Sellect topic show options');
define('_NEWS_AM_TOPIC_OPTIONS_DESC', 'Sellect topic show options');
define('_NEWS_AM_TOPIC_ALIAS', 'الاسم المستعار');
define('_NEWS_AM_TOPIC_SHOWTYPE_0', 'Module based');
define('_NEWS_AM_TOPIC_SHOWTYPE_1', 'News type');
define('_NEWS_AM_TOPIC_SHOWTYPE_2', 'Table type');
define('_NEWS_AM_TOPIC_SHOWTYPE_3', 'Photo type');
define('_NEWS_AM_TOPIC_SHOWTYPE_4', 'List type');
define('_NEWS_AM_TOPIC_SHOWTYPE_5', 'Spotlight');
define('_NEWS_AM_TOPIC_STYLE', 'Topic Style');

// Content page
define('_NEWS_AM_STORY_FORM', 'إدارة المحتوى');
define('_NEWS_AM_STORY_FORMTITLE', 'العنوان');
define('_NEWS_AM_STORY_FORMTITLE_DISP', 'عرض عنوان الصفحة؟');
define('_NEWS_AM_STORY_FORMAUTHOR', 'المحرر ( الإسم)');
define('_NEWS_AM_STORY_FORMSOURCE', 'مصدر ( الرابط)');
define('_NEWS_AM_STORY_FORMTEXT', 'النص');
define('_NEWS_AM_STORY_FORMTEXT_DESC', ' إنشاء أو تحرير صفحة');
define('_NEWS_AM_STORY_FORMGROUP', 'المجموعات');
define('_NEWS_AM_STORY_FORMALIAS', 'الإسم  المستعار');
define('_NEWS_AM_STORY_FORMACTIF', 'نشیط');
define('_NEWS_AM_STORY_IMPORTANT', 'عاجل');
define('_NEWS_AM_STORY_FORMDEFAULT', 'الإفتراضي');
define('_NEWS_AM_STORY_FORMPREV', 'السابقة');
define('_NEWS_AM_STORY_FORMNEXT', 'اللاحقة');
define('_NEWS_AM_STORY_DOHTML', 'العرض علی شکل Html');
define('_NEWS_AM_STORY_BREAKS', 'تبدیل خط شکسته فعال');
define('_NEWS_AM_STORY_DOIMAGE', 'عرض صورة النص');
define('_NEWS_AM_STORY_DOXCODE', 'عرض کود النص');
define('_NEWS_AM_STORY_DOSMILEY', 'عرض لبخند های محتوا');
define('_NEWS_AM_STORY_SHORT', 'الملخص');
define('_NEWS_AM_STORY_TITLE', 'العنوان');
define('_NEWS_AM_STORY_MANAGER', 'إدارة المحتوی');
define('_NEWS_AM_STORY_FILE', 'File');
define('_NEWS_AM_STORY_ID', 'ID');
define('_NEWS_AM_STORY_NUM', 'وزن');
define('_NEWS_AM_STORY_PAGE', 'الصفحة');
define('_NEWS_AM_STORY_TYPE', 'النوع');
define('_NEWS_AM_STORY_OWNER', 'المحرر');
define('_NEWS_AM_STORY_ACTIF', 'نشیط');
define('_NEWS_AM_STORY_DEFAULT', 'المقترض');
define('_NEWS_AM_STORY_ORDER', 'النظام');
define('_NEWS_AM_STORY_ACTION', 'العامل');
define('_NEWS_AM_STORY_VIEW', 'العرض');
define('_NEWS_AM_STORY_EDIT', 'ویرایش');
define('_NEWS_AM_STORY_DELETE', 'الغاء');
define('_NEWS_AM_STORY_SHORTDESC', 'بیان الملخص');
define('_NEWS_AM_STORY_TOPIC', 'فئة');
define('_NEWS_AM_STORY_TOPIC_DESC', 'إذ لم یتم إختیار فئة معینة، ستکون صفحتک ثابتة');
define('_NEWS_AM_STORY_STATIC', 'صفحة ثابتة');
define('_NEWS_AM_STORY_STATICS', 'صفحات متغیرة');
define('_NEWS_AM_STORY_ALL_ITEMS', 'جمیع الصفحات و الفهرسة من جمیع الفئات');
define('_NEWS_AM_STORY_ALL_ITEMS_FROM', 'جمیع الصفحات و الفهرسة من فئة :');
define('_NEWS_AM_STORY_FILE_DESC', 'For add more files you must use admin file system in admin side');
define('_NEWS_AM_STORY_SUBTITLE', 'Subtitle');
define('_NEWS_AM_STORY_ALL', 'All News');
define('_NEWS_AM_STORY_OFFLINE', 'الاخبار الغير منشورة');
define('_NEWS_AM_STORY_EXPIRE', 'الاخبار المنتهية الصلاحية');
define('_NEWS_AM_STORY_PEDATE', 'Set publish and expiration date');
define('_NEWS_AM_STORY_SETDATETIME', 'Set the date/time of publish');
define('_NEWS_AM_STORY_SETEXPDATETIME', 'Set the date/time of expiration');
define('_NEWS_AM_STORY_SLIDE', 'الفلاش الاخباري');
define('_NEWS_AM_STORY_MARQUE', 'الشريط الاخباري');
define('_NEWS_AM_STORY_OPTIONS', 'Options');
// Tools page
define('_NEWS_AM_TOOLS_FORMFOLDER_TITLE', 'استنساخ النسخ المتماثلة');
define('_NEWS_AM_TOOLS_FORMFOLDER_NAME', 'اسم المجلد');
define('_NEWS_AM_TOOLS_LOG_TITLE', 'تقریر استنساخ الوحدة');
define('_NEWS_AM_TOOLS_FORMPURGE_TITLE', 'Purge page of deleted clone');
define('_NEWS_AM_TOOLS_ALIAS_TITLE', 'تحدیث الإسم');
define('_NEWS_AM_TOOLS_ALIAS_CONTENT', 'تحدیث إسم الصفحة');
define('_NEWS_AM_TOOLS_ALIAS_TOPIC', 'تحدیث إسم الفئة');
define('_NEWS_AM_TOOLS_META_TITLE', 'Rebuild Metas');
define('_NEWS_AM_TOOLS_META_KEYWORD', 'Rebuild Meta keywords');
define('_NEWS_AM_TOOLS_META_DESCRIPTION', 'Rebuild Meta Description');
define('_NEWS_AM_TOOLS_PRUNE', 'Prune news');
define('_NEWS_AM_TOOLS_PRUNE_BEFORE', 'Prune stories that were published before');
define('_NEWS_AM_TOOLS_PRUNE_EXPIREDONLY', 'Only remove stories who have expired');
define('_NEWS_AM_TOOLS_PRUNE_TOPICS', 'Limit to the following topics');
define('_NEWS_AM_TOOLS_PRUNE_EXPORT_DSC', 'If you dont check anything then all the topics will be used else only the selected topics will be used');
// Permissions
define('_NEWS_AM_PERMISSIONS_ACCESS', 'إتاحة العرض');
define('_NEWS_AM_PERMISSIONS_SUBMIT', 'إتاحة الإرسال');
define('_NEWS_AM_PERMISSIONS_GLOBAL', 'أتاحة عامة');
define('_NEWS_AM_PERMISSIONS_GLOBAL_4', 'مشارکة');
define('_NEWS_AM_PERMISSIONS_GLOBAL_8', 'الإرسال في قسم المستخدمین');
define('_NEWS_AM_PERMISSIONS_GLOBAL_16', 'الموافقة التلقائية ');
// Attach files
define('_NEWS_AM_FILE', 'File');
define('_NEWS_AM_FILE_ID', 'ID');
define('_NEWS_AM_FILE_ONLINE', 'اونلاین');
define('_NEWS_AM_FILE_ACTION', 'نشیط');
define('_NEWS_AM_FILE_FORM', ' إضافة ملف ');
define('_NEWS_AM_FILE_TITLE', 'العنوان');
define('_NEWS_AM_FILE_CONTENT', 'الصفحة');
define('_NEWS_AM_FILE_STATUS', 'نشیط');
define('_NEWS_AM_FILE_SELECT', 'اختیار ملف');
define('_NEWS_AM_FILE_TYPE', 'النوع');
// Admin message
define('_NEWS_AM_MSG_DBUPDATE', 'تم تحدیث قاعدة بیانات!');
define('_NEWS_AM_MSG_ERRORDELETE', 'لایمکنک إلغاء هذه الوثیقة <br />الرجاء إلغاء أو نقل جمیع الوثائق التالیة');
define('_NEWS_AM_MSG_WAIT', 'انتظر قلیلا !');
define('_NEWS_AM_MSG_DELETE', 'هل أنت متأکد للحذف؟');
define('_NEWS_AM_MSG_EDIT_ERROR', ' لم يتم العثور على هذه الصفحة أورقم الصفحة غير صحيح !');
define('_NEWS_AM_MSG_UPDATE_ERROR', ' غير قادر على تحديث قاعدة البيانات! خطأ في تحديث الصفحة ');
define('_NEWS_AM_MSG_INSERT_ERROR', 'غير قادر على تحديث قاعدة البيانات! خطأ في الموضوع ');
define('_NEWS_AM_MSG_CLONE_ERROR', 'هذا الدليل هو متاح الآن !');
define("_NEWS_AM_MSG_NOPERMSSET", "لایمکن تعدیل الإتاحات: لم یتم تحدیث أي فئة ! الرجاء تحدیث فئة أولا.");
define('_NEWS_AM_MSG_ALIASERROR', 'لقد تم اختیار هذا الإسم. الرجاء اختیار اسم آخر.');
define('_NEWS_AM_MSG_INPROC', 'Rebuilding ... ');
define('_NEWS_AM_MSG_PRUNE_DELETED', '%s Articles deleted');
// about	
define('_NEWS_AM_ABOUT_ADMIN', 'درباره');
define('_NEWS_AM_ABOUT_DESCRIPTION', 'وصف:');
define('_NEWS_AM_ABOUT_AUTHOR', 'المتنج:');
define('_NEWS_AM_ABOUT_CREDITS', 'معارفه:');
define('_NEWS_AM_ABOUT_LICENSE', 'إتاحة:');
define('_NEWS_AM_ABOUT_MODULE_INFO', 'معلومات الوحدة:');
define('_NEWS_AM_ABOUT_RELEASEDATE', 'ساعة النشر:');
define("_NEWS_AM_ABOUT_UPDATEDATE", "ساعة التحدیث: ");
define('_NEWS_AM_ABOUT_MODULE_STATUS', 'الوضع:');
define('_NEWS_AM_ABOUT_WEBSITE', 'الموقع:');
define('_NEWS_AM_ABOUT_AUTHOR_INFO', 'معلومات المنتج');
define('_NEWS_AM_ABOUT_AUTHOR_NAME', 'الإسم:');
define('_NEWS_AM_ABOUT_CHANGELOG', 'قائمة التعدیلات');

?>