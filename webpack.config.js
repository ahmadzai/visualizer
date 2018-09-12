var Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
// directory where compiled assets will be stored
    .setOutputPath('web/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .setPublicPath(Encore.isProduction() ? '/visualizer/web/build' : '/visualizer/web/build')
    .setManifestKeyPrefix('build')
    .createSharedEntry('layout', './assets/js/layout.js')

    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('main-filter', './assets/js/main_filter.js')
    .addEntry('cluster-filter', './assets/js/cluster_filter.js')
    .addEntry('ccs-sm-filter', './assets/js/ccs_sm_filter.js')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    //.enableVersioning(/*Encore.isProduction()*/)
    .enableBuildNotifications()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use Sass/SCSS files
    //.enableSassLoader()
    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    // .autoProvideVariables({
    //     $: 'jquery',
    //     jQuery: 'jquery',
    //     'window.jQuery': 'jquery',
    // })
    .addPlugin(new CopyWebpackPlugin([
        // copies to {output}/static
        { from: './assets/static', to: 'static' }
    ]))

    .configureBabel(function(babelConfig) {
        babelConfig.presets.push('stage-2');
        // babelConfig.plugins = [
        //     "transform-es2015-destructuring",
        //     "transform-class-properties"
        // ]
    })


;

module.exports = Encore.getWebpackConfig();