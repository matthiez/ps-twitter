$(function () {
    $("#configuration_form").validate({
        rules: {
            "config[SHMO_TWITTERTOOL_WIDGETID]": {
                required: true
            },
            "config[SHMO_TWITTERTOOL_USERNAME]": {
                required: true
            },
            "config[SHMO_TWITTERTOOL_TWEET_COUNT]": {
                required: false,
                range: [1, 20]
            }
        },
        messages: {
            "config[SHMO_TWITTERTOOL_WIDGETID]": {
                required: "You to have specify an Widget ID."
            },
            "config[SHMO_TWITTERTOOL_USERNAME]": {
                required: "You have to specify the Username belonging to the Widget ID."
            },
            "config[SHMO_TWITTERTOOL_TWEET_COUNT]": {
                required: "The tweet count must be a number between 1 and 20."
            }
        }
    });
});