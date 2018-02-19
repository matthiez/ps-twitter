<div class="twitter-tool">

	<a class="twitter-tool-timeline"
	data-widget-id="{$shmotwttrtl.SHMO_TWITTERTOOL_WIDGETID}"
	href="https://twitter.com/{$shmotwttrtl.SHMO_TWITTERTOOL_USERNAME}" 
	data-screen-name="{$shmotwttrtl.SHMO_TWITTERTOOL_USERNAME}"
	data-chrome="{if $shmotwttrtl.SHMO_TWITTERTOOL_NO_HEADER}noheader{/if}
				 {if $shmotwttrtl.SHMO_TWITTERTOOL_NO_FOOTER}nofooter{/if}
				 {if $shmotwttrtl.SHMO_TWITTERTOOL_NO_BORDERS}noborders{/if}
				 {if $shmotwttrtl.SHMO_TWITTERTOOL_NO_SCROLLBAR}noscrollbar{/if}
				 {if $shmotwttrtl.SHMO_TWITTERTOOL_BG_TRANSPARENCY}}transparent{/if}"
	{if $shmotwttrtl.SHMO_TWITTERTOOL_WIDGET_WIDTH}width="{$shmotwttrtl.SHMO_TWITTERTOOL_WIDGET_WIDTH}"{/if}
	{if $shmotwttrtl.SHMO_TWITTERTOOL_WIDGET_HEIGHT}height="{$shmotwttrtl.SHMO_TWITTERTOOL_WIDGET_HEIGHT}"{/if}
	{if $shmotwttrtl.SHMO_TWITTERTOOL_TWEET_COUNT}data-tweet-limit="{$shmotwttrtl.SHMO_TWITTERTOOL_TWEET_COUNT}"{/if}
	{if $shmotwttrtl.SHMO_TWITTERTOOL_THEME}data-theme="dark"{else}data-theme="light"{/if}
	{if $shmotwttrtl.SHMO_TWITTERTOOL_LINK_COLOR}data-link-color="{$shmotwttrtl.SHMO_TWITTERTOOL_LINK_COLOR}"{/if}
	{if $shmotwttrtl.SHMO_TWITTERTOOL_BORDER_COLOR}data-border-color="{$shmotwttrtl.SHMO_TWITTERTOOL_BORDER_COLOR}"{/if}
	{if $shmotwttrtl.SHMO_TWITTERTOOL_ASSERTIVE_POLITENESS}data-aria-polite="{$shmotwttrtl.SHMO_TWITTERTOOL_ASSERTIVE_POLITENESS}"{/if}
	>
	</a>
	
</div>

<script>{literal}!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");{/literal}</script>

