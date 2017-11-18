<{includeq file="$xoops_rootpath/modules/news/templates/admin/news_header.tpl"}>

<div class="moduleicons">
    <div class="floatleft">
        <div class="xo-buttons">
            <a class="ui-corner-all tooltip" href="article.php" title="<{$smarty.const._NEWS_AM_STORY_ALL}>">
                <img src="<{xoAdminIcons add.png}>" alt="<{$smarty.const._NEWS_AM_STORY_ALL}>"/>
                <{$smarty.const._NEWS_AM_STORY_ALL}>
            </a>

            <a class="ui-corner-all tooltip" href="article.php?topic=0"
               title="<{$smarty.const._NEWS_AM_STORY_STATICS}>">
                <img src="<{xoAdminIcons add.png}>" alt="<{$smarty.const._NEWS_AM_STORY_STATICS}>"/>
                <{$smarty.const._NEWS_AM_STORY_STATICS}>
            </a>

            <a class="ui-corner-all tooltip" href="article.php?op=offline"
               title="<{$smarty.const._NEWS_AM_STORY_OFFLINE}>">
                <img src="<{xoAdminIcons add.png}>" alt="<{$smarty.const._NEWS_AM_STORY_OFFLINE}>"/>
                <{$smarty.const._NEWS_AM_STORY_OFFLINE}>
            </a>

            <a class="ui-corner-all tooltip" href="article.php?op=expire"
               title="<{$smarty.const._NEWS_AM_STORY_EXPIRE}>">
                <img src="<{xoAdminIcons add.png}>" alt="<{$smarty.const._NEWS_AM_STORY_EXPIRE}>"/>
                <{$smarty.const._NEWS_AM_STORY_EXPIRE}>
            </a>
        </div>
    </div>
    <div class="floatright bold marg5">
        <{if $topic_title}>
        <{$smarty.const._NEWS_AM_STORY_ALL_ITEMS_FROM}><{$topic_title}>
        <{else}>
        <{$smarty.const._NEWS_AM_STORY_ALL_ITEMS}>
        <{/if}>
    </div>
</div>

<table id="xo-content-sort" class="outer" cellspacing="1" width="100%">
    <thead>
    <th><{$smarty.const._NEWS_AM_STORY_ID}></th>
    <th><{$smarty.const._NEWS_AM_STORY_TITLE}></th>
    <th><{$smarty.const._NEWS_AM_STORY_TOPIC}></th>
    <th><{$smarty.const._NEWS_AM_STORY_TYPE}></th>
    <th><{$smarty.const._NEWS_AM_STORY_OWNER}></th>
    <th><{$smarty.const._NEWS_AM_STORY_ACTIF}></th>
    <th><{$smarty.const._NEWS_AM_STORY_DEFAULT}></th>
    <th><{$smarty.const._NEWS_AM_STORY_IMPORTANT}></th>
    <th><{$smarty.const._NEWS_AM_STORY_PUSH}></th>
    <th><{$smarty.const._NEWS_AM_STORY_ACTION}></th>
    </thead>
    <tbody class="xo-content">
    <{foreach item=story from=$contents}>
    <tr class="odd" id="mod_<{$story.story_id}>">
        <td class="width5 txtcenter"><{$story.story_id}></td>
        <td class="width30 txtcenter"><a href="<{$story.url}>"><{$story.story_title}></a></td>
        <td class="txtcenter width10 bold">
            <{if $story.topic}>
            <a href="article.php?topic=<{$story.story_topic}>"><{$story.topic}></a>
            <{else}>
            <a href="article.php?topic=0"><{$smarty.const._NEWS_AM_STORY_STATIC}></a>
            <{/if}>
        </td>
        <td class="txtcenter width10 bold">
            <img src="../images/icons/<{$story.story_type}>.png" alt=""/><{$story.story_type}>
        </td>
        <td class="txtcenter width10">
            <a href="<{$xoops_url}>/userinfo.php?uid=<{$story.story_uid}>"><img class="tooltip"
                                                                                src="../images/icons/user.png"
                                                                                alt="<{$story.owner}>"
                                                                                title="<{$story.owner}>"/>&nbsp;<{$story.owner}></a>
        </td>
        <td class="txtcenter width5">
            <img class="cursorpointer" id="status<{$story.story_id}>"
                 onclick="news_setStatus( { op: 'status', story_id: <{$story.story_id}> }, 'status<{$story.story_id}>', 'backend.php' )"
                 src="<{if $story.story_status}>../images/icons/ok.png<{else}>../images/icons/cancel.png<{/if}>"
                 alt=""/>
        </td>
        <td class="txtcenter width5">
            <img class="cursorpointer xo-defaultimg" id="default<{$story.story_id}>"
                 onclick="news_setDefault( { op: 'default', story_id: <{$story.story_id}> , topic_id: <{$story.story_topic}> }, 'default<{$story.story_id}>', 'backend.php' )"
                 src="<{if $story.story_default}>../images/icons/ok.png<{else}>../images/icons/cancel.png<{/if}>"
                 alt=""/>
        </td>
        <td class="txtcenter width5">
            <img class="cursorpointer" id="important<{$story.story_id}>"
                 onclick="news_setImportant( { op: 'important', story_id: <{$story.story_id}> }, 'important<{$story.story_id}>', 'backend.php' )"
                 src="<{if $story.story_important}>../images/icons/ok.png<{else}>../images/icons/cancel.png<{/if}>"
                 alt=""/>
        </td>
        <td class="txtcenter width5">
            <img class="cursorpointer" id="push<{$story.story_id}>" style="cursor:pointer;"
                 onclick="news_setPush( { op: 'push', story_id: <{$story.story_id}> }, 'push<{$story.story_id}>', 'backend.php' )"
                 src="../images/icons/idea.png"
                 alt=""/>
        </td>
        <td class="txtcenter xo-actions width15">
            <img class="tooltip" onclick="display_dialog(<{$story.story_id}>, true, true, 'slide', 'slide', 300, 700);"
                 src="<{xoAdminIcons display.png}>" alt="<{$smarty.const._PREVIEW}>"
                 title="<{$smarty.const._PREVIEW}>"/>
            <a href="<{$story.url}>"><img class="tooltip" src="../images/icons/display.png"
                                          alt="<{$smarty.const._NEWS_AM_STORY_VIEW}>"
                                          title="<{$smarty.const._NEWS_AM_STORY_VIEW}>"/></a>
            <{if $story.story_file != 0}>
            <a href="file.php?content=<{$story.story_id}>"><img class="tooltip" src="../images/icons/file.png"
                                                                alt="<{$smarty.const._NEWS_AM_STORY_FILE}>"
                                                                title="<{$smarty.const._NEWS_AM_STORY_FILE}>"/></a>
            <{/if}>
            <{if $story.story_topic != 0}>
            <a href="<{$story.topicurl}>"><img class="tooltip" src="../images/icons/section.png"
                                               alt="<{$smarty.const._NEWS_AM_STORY_TOPIC}>"
                                               title="<{$smarty.const._NEWS_AM_STORY_TOPIC}>"/></a>
            <{/if}>
            <a href="article.php?op=edit_content&amp;story_id=<{$story.story_id}>"><img class="tooltip"
                                                                                        src="../images/icons/edit.png"
                                                                                        alt="<{$smarty.const._EDIT}>"
                                                                                        title="<{$smarty.const._EDIT}>"/></a>
            <a href="article.php?op=delete&amp;story_id=<{$story.story_id}>"><img class="tooltip"
                                                                                  src="../images/icons/delete.png"
                                                                                  alt="<{$smarty.const._DELETE}>"
                                                                                  title="<{$smarty.const._DELETE}>"/></a>
        </td>
    </tr>
    <{/foreach}>
    </tbody>
</table>

<{foreach item=item from=$contents}>
<div id="dialog<{$item.story_id}>" title="<{$item.story_title}>" style='display:none;'>
    <div class="ui-state-active ui-corner-all pad5 bold">
        <div class="bold"><{$smarty.const._NEWS_AM_STORY_TITLE}> : <a href="<{$item.url}>"><{$item.story_title}></a>
        </div>
        <div class="bold"><{$smarty.const._NEWS_AM_STORY_TOPIC}> :
            <{if $item.topic}>
            <a href="article.php?topic=<{$item.story_topic}>"><{$item.topic}></a>
            <{else}>
            <a href="article.php?topic=0"><{$smarty.const._NEWS_AM_STORY_STATIC}></a>
            <{/if}>
        </div>
    </div>
    <div class="pad5"><img class="ui-state-highlight left" width="200" src="<{$item.thumburl}>"
                           alt="<{$item.story_title}>"/></div>
    <div class="pad5"><{$item.story_short}></div>
</div>
<{/foreach}>

<div class="pagenav"><{$story_pagenav}></div>