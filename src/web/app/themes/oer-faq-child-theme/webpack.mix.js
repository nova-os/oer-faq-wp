const mix = require('laravel-mix');

mix.options({
  // Don't perform any css url rewriting by default
  processCssUrls: false,
})

mix.sass('scss/custom.scss', 'css')
mix.sass('scss/admin.scss', 'css')
