const mix = require('laravel-mix');
require('laravel-mix-purgecss');
// var WebpackObfuscator = require('webpack-obfuscator');
// require('laravel-mix-obfuscator');
// var path = require('path');


if (mix.inProduction()) {
    var options = {
        clearConsole: true,
        postCss: [
            require('postcss-discard-comments')({
                removeAll: true
            })
        ],
        uglify: {
            comments: false
        },
    };
    mix.version();
}else{
    var options = {
        postCss: [
            require('postcss-discard-comments')({
                removeAll: true
            })
        ],
        uglify: {
            comments: false
        },
    };
}

mix.options(options);

/*mix.obfuscator({
        compact: true,
        rotateStringArray: true,
        shuffleStringArray: true,
        debugProtection: true,
        target: 'browser',
    exclude: [path.resolve(__dirname, 'node_modules')]
});*/

mix.js('resources/js/admin.js', 'public/assets/admin/js');
mix.js('resources/js/plugins.js', 'public/assets/admin/js');
mix.sass('resources/sass/plugins.scss', 'public/assets/admin/css');
mix.sass('resources/sass/admin.scss', 'public/assets/admin/css');

mix.sass('resources/sass/web.scss', 'public/assets/web/css');
mix.js('resources/js/web.js', 'public/assets/web/js');

/*.purgeCss({
    whitelist: ['row-cols-2','row-cols-3','row-cols-*']
});*/

