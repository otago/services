const mix = require('laravel-mix')

mix
    .js(['./js/app.js'], './dist/app.js')
    .vue({ version: 3 });
