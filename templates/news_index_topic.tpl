<div class="news-index-topic">
    <div class="news-topicinfo">
        <div class="topic_header">
            <{if $story_topic}>
            <h2><{$topic_title}></h2>
            <{/if}>
        </div>
        <{if $topic_img || $topic_desc}>
        <div class="itemBody">
            <{if $topic_img}>
            <div class="itemImg gallery">
                <{if $img_lightbox}>
                <a href="<{$topic_medium}>" title="<{$topic_title}>">
                    <img width="<{$imgwidth}>" class="<{$imgfloat}> story_img" src="<{$topic_thumb}>"
                         alt="<{$topic_title}>"/>
                </a>
                <{else}>
                <img width="<{$imgwidth}>" class="<{$imgfloat}> story_img" src="<{$topic_thumb}>"
                     alt="<{$topic_title}>"/>
                <{/if}>
            </div>
            <{/if}>
            <{$topic_desc}>
            <div class="clear"></div>
        </div>
        <{/if}>
    </div>
    <{if !$topic_sub}>
    <div class="subTopicList">
        <h4><{$smarty.const._NEWS_MD_SUBTOPICS}></h4>
        <ul>
            <{foreach item=topic from=$sub_topic}>
            <li class="width33 floatleft"><a title="<{$topic.topic_title}>" href="<{$topic.topic_url}>"><{$topic.topic_title}></a>
            </li>
            <{/foreach}>
        </ul>
        <div class="clear"></div>
    </div>
    <{/if}>
</div>