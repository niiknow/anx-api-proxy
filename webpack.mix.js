const path                = require('path');
const mix                 = require('laravel-mix');

const source = 'resources';
const public = 'public';

mix.setPublicPath(path.normalize(public));

mix.webpackConfig({
  externals: {
    'jquery': 'jQuery',
    'vue': 'Vue'
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(vue|js)$/,
        exclude: /(node_modules|bower_components)/,
        loader: 'eslint-loader',
        options: {
          fix: false,
          cache: false,
          formatter: require('eslint-friendly-formatter')
        }
      }
    ]
  },
  devServer: { overlay: true },
  devtool: 'source-map',
  resolve: {
    /* Path Shortcuts */
    alias:{
      /* root */
      '~': path.resolve(__dirname, `${ source }/js`),
      Components: path.resolve(__dirname, `${ source }/js/components`),
      Layouts: path.resolve(__dirname, `${ source }/js/layouts`),
      Pages: path.resolve(__dirname, `${ source }/js/pages`)
    }
  }
});

mix.js(`${ source }/js/myapp.js`, `${ public }/js`);
mix.sass(`${ source }/sass/myapp.scss`, `${ public }/css`, {
  outputStyle: mix.inProduction() ? 'compact' : 'expanded'
});
mix.sourceMaps();
mix.browserSync({
  proxy: 'anx-api-proxy.test',
  host: 'anx-api-proxy.test',
  files: [
    `${ source }/views/**/*.php`,
    `${ public }/js/**/*.js`,
    `${ public }/css/**/*.css`
  ],
  browser: 'firefox',
  ghostMode: false,
  open: 'external'
});

mix.extract([
  'vue'
]);

mix.version();
if (mix.inProduction()) {
  mix.disableNotifications();
}


