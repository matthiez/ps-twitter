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

$(document).ready(function () {
    const TWEET_MIN = 1;
    const TWEET_MAX = 20;

    $('#configuration_form').validate({
        rules: {
            "config[EOO_TWITTER_USERNAME]": {
                required: true
            },
            "config[EOO_TWITTER_TWEET_COUNT]": {
                required: false,
                range: [TWEET_MIN, TWEET_MAX]
            }
        },

        messages: {
            "config[EOO_TWITTER_USERNAME]": {
                required: "You have to specify a Username."
            },
            "config[EOO_TWITTER_TWEET_COUNT]": {
                range: `The tweet count must be a number between ${TWEET_MIN} and ${TWEET_MAX}.`
            }
        }
    });
});