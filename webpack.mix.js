const mix = require('laravel-mix')

mix.setPublicPath('public')
  .js('resources/js/upload.js', 'public/assets/js')
  .js('resources/js/register.js', 'public/assets/js')
  .js('resources/js/index.js', 'public/assets/js')
  .js('resources/js/file.js', 'public/assets/js')
  .js('resources/js/files.js', 'public/assets/js')
  .extract(['jquery', 'materialize-css/dist/js/materialize', 'wavesurfer.js'])
  .sass('resources/sass/app.scss', 'public/assets/css')
  .version()
