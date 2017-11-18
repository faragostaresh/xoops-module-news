<{foreach item=topic from=$topics}>
<tr class="<{$level}>">
    <td class="top">
        <div class="topicTitle"><a title="<{$topic.topic_title}>" href="<{$topic.topicurl}>"><{$topic.topic_title}></a><{if
            $topic.count}> ( <{$topic.count}> ) <{/if}>
        </div>
        <div class="topicDesc">
            <{if $topic.topic_img}>
            <div class="gallery">
                <{if $img_lightbox}>
                <a href="<{$topic.imageurl}>" title="<{$topic.topic_title}>">
                    <img width="<{$imgwidth}>" class="<{$imgfloat}> story_img" src="<{$topic.thumburl}>"
                         alt="<{$topic.topic_title}>"/>
                </a>
                <{else}>
                <img width="<{$imgwidth}>" class="<{$imgfloat}> story_img" src="<{$topic.thumburl}>"
                     alt="<{$topic.topic_title}>"/>
                <{/if}>
            </div>
            <{/if}>
            <{$topic.topic_desc}>
        </div>
    </td>
</tr>
<{if $topic.topic_children}>
<{include file="db:news_topic_list.tpl" topics=$topic.topic_children level='even subLevel'}>
<{/if}>
<{/foreach}>