{if $TWITTERTOOL_USERNAME}
    <div class="twitter-tool">
        <a class="twitter-timeline" href="https://twitter.com/{$TWITTERTOOL_USERNAME}" data-screen-name="{$TWITTERTOOL_USERNAME}"
        data-chrome="{if $TWITTERTOOL_NO_HEADER}noheader{/if}
            {if $TWITTERTOOL_NO_FOOTER}nofooter{/if}
            {if $TWITTERTOOL_NO_BORDERS}noborders{/if}
            {if $TWITTERTOOL_NO_SCROLLBAR}noscrollbar{/if}
            {if $TWITTERTOOL_BG_TRANSPARENCY}}transparent{/if}"
        style="{if $WIDGET_WIDTH}width:{$WIDGET_WIDTH}px;{/if} {if $WIDGET_HEIGHT}height:{$WIDGET_HEIGHT}px;{/if}"
        {if $TWITTERTOOL_TWEET_COUNT}data-tweet-limit="{$TWITTERTOOL_TWEET_COUNT}"{/if}
        {if $TWITTERTOOL_THEME}data-theme="dark"{else}data-theme="light"{/if}
        {if $TWITTERTOOL_LINK_COLOR}data-link-color="{$TWITTERTOOL_LINK_COLOR}"{/if}
        {if $TWITTERTOOL_BORDER_COLOR}data-border-color="{$TWITTERTOOL_BORDER_COLOR}"{/if}
        {if $TWITTERTOOL_ASSERTIVE_POLITENESS}data-aria-polite="{$TWITTERTOOL_ASSERTIVE_POLITENESS}"{/if}
        ></a>
    </div>
{/if}