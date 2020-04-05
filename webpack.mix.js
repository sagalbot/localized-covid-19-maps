const mix = require('laravel-mix');

const purgecss = require('@fullhuman/postcss-purgecss')({
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.vue',
    './node_modules/vue-select/src/**/*.vue'
  ],
  defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
});

require('laravel-mix-svg-vue');

mix.js('resources/js/app.js', 'public/js');

mix.postCss('resources/css/app.css', 'public/css', [
  require('tailwindcss'),
  require('autoprefixer'),
  ...(process.env.NODE_ENV === 'production' ? [purgecss] : [])
]);

mix.webpackConfig(webpack => ({
  resolve: {
    alias: {
      '@': path.resolve('resources/js'),
      ziggy: path.resolve('vendor/tightenco/ziggy/dist/js/route.js')
    }
  }
}));

mix.svgVue();
