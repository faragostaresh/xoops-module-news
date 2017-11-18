<{include file="db:news_header.tpl"}>
<div class="news">
    <{if $sub_topic}>
    <{include file="db:news_index_topic.tpl"}>
    <{/if}>
    <{if $advertisement}>
    <div class="itemAde"><{$advertisement}></div>
    <{/if}>
    <{if $type == type1 || $type == type3}>
    <{if $default}>
    <{include file="db:news_index_default.tpl"}>
    <{/if}>
    <{if $showtype == 1 && ($story_limit != 0 || !$story_topic)}>
    <{include file="db:news_index_news.tpl"}>
    <{elseif $showtype == 2 && ($story_limit != 0 || !$story_topic)}>
    <{include file="db:news_index_table.tpl"}>
    <{elseif $showtype == 3 && ($story_limit != 0 || !$story_topic)}>
    <{include file="db:news_index_photo.tpl"}>
    <{elseif $showtype == 4 && ($story_limit != 0 || !$story_topic)}>
    <{include file="db:news_index_list.tpl"}>
    <{/if}>
    <{if $story_pagenav}>
    <div class="pagenave"><{$story_pagenav}></div>
    <{/if}>
    <{elseif $type == type2}>
    <table id="xo-content-data" class="outer" cellspacing="1" width="100%">
        <thead>
        <th><{$smarty.const._NEWS_MD_TOPIC_ID}></th>
        <th><{$smarty.const._NEWS_MD_TOPIC_NAME}></th>
        <th><{$smarty.const._NEWS_MD_TOPIC_DESC}></th>
        <th><{$smarty.const._NEWS_MD_TOPIC_IMG}></th>
        </thead>
        <tbody>
        <{foreach item=topic from=$contents}>
        <tr class="odd">
            <td class="width5"><img src="images/icons/puce.png" alt="<{$topic.topic_title}>"/><{$topic.topic_id}></td>
            <td class="txtcenter bold top"><a title="<{$topic.topic_title}>" href="<{$topic.topicurl}>"><{$topic.topic_title}></a>
            </td>
            <td
            <{if !$topic.topic_img}>colspan="2"<{/if}> class="top"><{$topic.topic_desc}></td>
            <{if $topic.topic_img}>
            <td class="top"><img width="<{$imgwidth}>" class="<{$imgfloat}> story_img" src="<{$topic.imgurl}>"
                                 alt="<{$topic.topic_title}>"/></td>
            <{/if}>
        </tr>
        <{/foreach}>
        </tbody>
    </table>
    <{elseif $type == type4}>
    <{include file="db:news_index_default.tpl"}>
    <{/if}>
</div>