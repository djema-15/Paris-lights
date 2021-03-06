var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
   // .addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')
    .addEntry('image', './assets/images/logo.jpg')
    .addEntry('image1', './assets/images/StreetAround.png')
    .addEntry('velib', './assets/images/velib.png')
    .addEntry('FAVICON', './assets/images/favicon.ico')
    .addEntry('loader', './assets/images/loader.gif')
    .addEntry('logoevenments', './assets/images/logo-evenements.png')

    //js
    .addEntry('main', './assets/bootstrap/js/main.js')
    .addEntry('bootstrap.bundle.min', './assets/bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js')
    .addEntry('jquery.min', './assets/bootstrap/vendor/jquery/jquery.min.js')
    .addEntry('jquery.easing.min', './assets/bootstrap/vendor/jquery.easing/jquery.easing.min.js')
    .addEntry('jquery.waypoints.min', './assets/bootstrap/vendor/waypoints/jquery.waypoints.min.js')
    .addEntry('counterup.min', './assets/bootstrap/vendor/counterup/counterup.min.js')
    .addEntry('isotope.pkgd.min', './assets/bootstrap/vendor/isotope-layout/isotope.pkgd.min.js')
    .addEntry('venobox.min', './assets/bootstrap/vendor/venobox/venobox.min.js')
    .addEntry('owl.carousel.min', './assets/bootstrap/vendor/owl.carousel/owl.carousel.min.js')
    .addEntry('aos', './assets/bootstrap/vendor/aos/aos.js')
    //css
   
    .addEntry('bootstrap.min', './assets/bootstrap/vendor/bootstrap/css/bootstrap.min.css')
    .addEntry('icofont.min', './assets/bootstrap/vendor/icofont/icofont.min.css')
    .addEntry('boxicons.min', './assets/bootstrap/vendor/boxicons/css/boxicons.min.css')
    .addEntry('venobox', './assets/bootstrap/vendor/venobox/venobox.css')
    .addEntry('owl.carousel.min1', './assets/bootstrap/vendor/owl.carousel/assets/owl.carousel.min.css')
    .addEntry('aos1', './assets/bootstrap/vendor/aos/aos.css')
    .addEntry('style', './assets/bootstrap/css/style.css')

    
    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
