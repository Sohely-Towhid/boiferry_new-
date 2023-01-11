/**
 * Imperavi Redactor Plugin for Embedding Tweets
 * for version >= 9.1
 *
 * https://gist.github.com/jasonbyrne/6e96a907c781e90e0dbf
 *
 * @author Jason Byrne <jason.byrne@flocasts.com>
 * @version 0.5.1
 *
 * @forked https://gist.github.com/chekalskiy/7438084
 * @author Ilya Chekalskiy <ilya@chekalskiy.ru>
 * @version 0.2.2
 *
 */


if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.tweets = function() {

    var redactor = null;

    var tweetDialog = '<div class="tweets-plugin"><section><p><label>Embed Tweet:</label>' +
        '<input type="text" id="tweet_url" value="" placeholder="https://twitter.com/milesplit/status/545273015835971584" ' +
        'style="width: 100%" /></p><p><label>' +
        '<input type="checkbox" id="tweet_show_media" value="1" checked /> Show Media</label></p>' +
        '<p><label><input type="checkbox" id="tweet_show_thread" value="1" /> Show Tweet Thread</label></p></section>';

    return {

        init: function() {

            redactor = this;

            var button = this.button.addAfter('file', 'tweets', 'Tweets');
            this.button.addCallback(button, this.tweets.show);
            this.button.setAwesome('tweets', 'fa-twitter');

        },

        show: function()
        {
            this.modal.addTemplate('tweets', tweetDialog);

            this.modal.load('tweets', 'Tweets', 700);
            this.modal.createCancelButton();

            var button = this.modal.createActionButton(this.lang.get('insert'));
            button.on('click', redactor.tweets.insert);

            this.selection.save();
            this.modal.show();

            $('#redactor-insert-tweets').focus();

        },

        insert: function(html) {

            var tweet_url = $('#tweet_url').val(),
                tweet_hide_media = ($('#tweet_show_media').prop('checked') == true) ? 0 : 1,
                tweet_hide_thread = ($('#tweet_show_thread').prop('checked') == true) ? 0 : 1;

            $.get(
                'https://api.twitter.com/1/statuses/oembed.json',
                {
                    url: tweet_url,
                    omit_script: 1,
                    related: 'milesplit',
                    maxwidth: 620,
                    'lang': 'en',
                    hide_media: tweet_hide_media,
                    hide_thread: tweet_hide_thread
                },
                $.proxy(function(data) {

                    if (data.html) {

                        var html = data.html;

                        // If this hasn't already been included
                        if (!redactor.$editor.find('script[src="//platform.twitter.com/widgets.js"]').length) {
                            var $html = $(html).append(
                                '<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>'
                            );
                            html = redactor.utils.getOuterHtml($html);
                        }

                        console.log(html);

                        this.selection.restore();
                        this.modal.close();

                        var current = this.selection.getBlock() || this.selection.getCurrent();

                        this.insert.htmlWithoutClean(html);

                        this.code.sync();

                    }
                    else {
                        alert('Error loading tweet');
                    }

            }, this), 'jsonp');


        }

    };

};