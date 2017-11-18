<{include file="db:news_header.tpl"}>
<div class="news">
    <{if $advertisement}>
    <div class="itemAde"><{$advertisement}></div>
    <{/if}>
    <table class="outer">
        <th class="txtcenter"><{$smarty.const._NEWS_MD_TOPIC_LIST}></th>
        <{include file="db:news_topic_list.tpl" level='odd mainLevel'}>
    </table>
    <{if $topic_pagenav}>
    <div class="pagenave"><{$topic_pagenav}></div>
    <{/if}>
</div>