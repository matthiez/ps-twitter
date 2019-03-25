{*
/**
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement.
*
* You must not modify, adapt or create derivative works of this source code
*
*  @author    André Matthies
*  @copyright 2018-present André Matthies
*  @license   LICENSE
*/
*}

{if $EOO_TWITTER_USERNAME}
    <div class="twitter-tool">
        <a class="twitter-timeline" href="https://twitter.com/{$EOO_TWITTER_USERNAME}"
           data-screen-name="{$EOO_TWITTER_USERNAME}"
           data-chrome="{if $EOO_TWITTER_NO_HEADER}noheader{/if}
            {if $EOO_TWITTER_NO_FOOTER}nofooter{/if}
            {if $EOO_TWITTER_NO_BORDERS}noborders{/if}
            {if $EOO_TWITTER_NO_SCROLLBAR}noscrollbar{/if}
            {if $EOO_TWITTER_BG_TRANSPARENCY}}transparent{/if}"
           style="{if $WIDGET_WIDTH}width:{$WIDGET_WIDTH}px;{/if} {if $WIDGET_HEIGHT}height:{$WIDGET_HEIGHT}px;{/if}"
           {if $EOO_TWITTER_TWEET_COUNT}data-tweet-limit="{$EOO_TWITTER_TWEET_COUNT}"{/if}
                {if $EOO_TWITTER_THEME}data-theme="dark"{else}data-theme="light"{/if}
                {if $EOO_TWITTER_LINK_COLOR}data-link-color="{$EOO_TWITTER_LINK_COLOR}"{/if}
                {if $EOO_TWITTER_BORDER_COLOR}data-border-color="{$EOO_TWITTER_BORDER_COLOR}"{/if}
                {if $EOO_TWITTER_ASSERTIVE_POLITENESS}data-aria-polite="{$EOO_TWITTER_ASSERTIVE_POLITENESS}"{/if}
        ></a>
    </div>
{/if}