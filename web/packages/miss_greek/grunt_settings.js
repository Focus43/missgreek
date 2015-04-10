/**
 * Sass and compass install:
 * http://stackoverflow.com/questions/19068382/sass-compile-error-stalenesscheckermutex-nameerror
 */
module.exports.extraConfigs = function( grunt, _currentConfigs ){

    // Generate path to file in package root
    function pkgPath( _path ){
        var _pkgPath = '../web/packages/miss_greek/%s';
        return _pkgPath.replace('%s', _path);
    }


    /////////////////////////////// CONCAT FILES ///////////////////////////////
    _currentConfigs.concat.miss_greek = { files: {} };

    // concat theme public js
    _currentConfigs.concat.miss_greek.files[ pkgPath('js/miss-greek.js') ] = [
        pkgPath('js/libs/placeholder-shim.js'),
        pkgPath('js/libs/bootstrap-3.1.1/transition.js'),
        pkgPath('js/libs/bootstrap-3.1.1/collapse.js'),
        pkgPath('js/libs/bootstrap-3.1.1/tooltip.js'),
        pkgPath('js/libs/ajaxify.form.js'),
        pkgPath('js/dev/miss_greek.dev.js')
    ];

    // dependencies (highcharts and masonry)
    _currentConfigs.concat.miss_greek.files[ pkgPath('js/app.dependencies.js') ] = [
        pkgPath('js/libs/masonry.pkgd.js'),
        pkgPath('js/libs/highcharts-3.0.9/js/highcharts.js')
    ];

    // dashboard
    _currentConfigs.concat.miss_greek.files[ pkgPath('js/miss-greek.dashboard.js') ] = [
        pkgPath('js/dev/miss_greek.dashboard.dev.js')
    ];

    // qr code (independent)
    _currentConfigs.concat.miss_greek.files[ pkgPath('js/qrcode.js') ] = [
        pkgPath('js/libs/qrcode.js')
    ];


    /////////////////////////////// UGLIFY FILES ///////////////////////////////
    _currentConfigs.uglify.miss_greek = {
        options: {
            banner: '<%= banner %>',
            expand: true
        },
        files : {}
    };

    var _uglifyTargets = [
        pkgPath('js/miss-greek.js'),
        pkgPath('js/app.dependencies.js'),
        pkgPath('js/miss-greek.dashboard.js'),
        pkgPath('js/qrcode.js')
    ];

    for( var i = 0; i < _uglifyTargets.length; i++ ){
        _currentConfigs.uglify.miss_greek.files[ _uglifyTargets[i] ] = _uglifyTargets[i];
    };


    /////////////////////////////// SASS BUILDS ///////////////////////////////
    _currentConfigs.sass.miss_greek = {
        options  : {
            style: 'compressed',
            compass: true
        },
        files    : [
            // theme
            {src: [pkgPath('css/dev/miss_greek.scss')], dest: pkgPath('css/miss-greek.css')},
            // dashboard
            {src: [pkgPath('css/dev/miss_greek.dashboard.scss')], dest: pkgPath('css/miss-greek.dashboard.css')},
            // checkin single page
            {src: [pkgPath('css/dev/checkin.scss')], dest: pkgPath('css/checkin.css')}
        ]
    }

    // Watch Tasks
    _currentConfigs.watch.miss_greek_sass = {
        files : [pkgPath('**/*.dev.js'), pkgPath('css/**/*.scss')],
        tasks : ['newer:concat:miss_greek', 'sass_style_uncompressed', 'newer:sass:miss_greek']
    };

    _currentConfigs.watch.miss_greek_css = {
        options: {livereload: 9090},
        files: [pkgPath('css/*.css')],
        tasks: []
    }

    // During watch tasks, change the sass output style to expanded
    grunt.registerTask('sass_style_uncompressed', 'Modify SASS output style', function(){
        grunt.config('sass.miss_greek.options.style', 'expanded');
    });

    // Register the miss_greek task to dev all ($: grunt miss_greek)
    grunt.registerTask('mg_build', ['concat:miss_greek', 'uglify:miss_greek', 'sass:miss_greek']);


}