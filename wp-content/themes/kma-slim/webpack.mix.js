const mix = require("laravel-mix")
const purgeCss = require('purgecss-webpack-plugin')
const glob = require('glob')
const path = require('path')

mix.js('js/front.js', './').vue()
   .js('js/generic.js', './').vue()
   

mix.sass('sass/style.scss', './')
   .sass('sass/sections/map.scss', './');

mix.options({
    postCss: [
        require('autoprefixer')({
            grid: true,
            browsers: ['last 2 versions', 'IE 9', 'Safari 9']
        })
    ]
});