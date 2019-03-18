"use strict";

const TWEET_MIN = 1;
const TWEET_MAX = 20;

$(document).ready(function () {
    $('#configuration_form').validate({
        rules: {
            "config[TWITTERTOOL_USERNAME]": {
                required: true
            },
            "config[TWITTERTOOL_TWEET_COUNT]": {
                required: false,
                range: [TWEET_MIN, TWEET_MAX]
            }
        },

        messages: {
            "config[TWITTERTOOL_USERNAME]": {
                required: "You have to specify a Username."
            },
            "config[TWITTERTOOL_TWEET_COUNT]": {
                range: `The tweet count must be a number between ${TWEET_MIN} and ${TWEET_MAX}.`
            }
        }
    });
});