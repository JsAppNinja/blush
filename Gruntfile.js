'use strict';

module.exports = function (grunt) {

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take. Can help when optimizing build times
    require('time-grunt')(grunt);
    grunt.loadNpmTasks('grunt-cloudfiles');
    grunt.loadNpmTasks('grunt-cdn');
    grunt.loadNpmTasks('grunt-php-set-constant');
    // Define the configuration for all the tasks
    grunt.initConfig({
        app: {
            cdnUrl: 'http://joinblush.com',
            views: {
                includes: 'www/app/application/views/includes'
            },
            assets: {
                src: 'www/assets',
                dist: 'www/assets/dist',
                distHttp: '/assets/dist'
            }
        },
        // Empties folders to start fresh
        clean: {
            dist: {
                files: [
                    {
                        dot: true,
                        expand: true,
                        src: [
                            '.tmp',
                            '<%= app.assets.src%>/*.css',
                            '<%= app.assets.dist %>/{,*/}*',
                            '<%= app.views.includes%>/prod/{,*/}*'
                        ]
                    }
                ]
            }
        },

        jshint: {
            options: {
                jshintrc: '.jshintrc',
                reporter: require('jshint-stylish')
            },
            all: {
                src: [
                    'Gruntfile.js',
                    '<%= app.assets.src %>/scripts/app/{,*/}*.js'
                ]
            }
        },
        // Compiles Sass to CSS and generates necessary files if requested
        compass: {
            options: {
                sassDir: '<%= app.assets.src %>/sass',
                cssDir: '<%= app.assets.src %>/stylesheets',
                generatedImagesDir: '.tmp/images/generated',
                imagesDir: '<%= app.assets.src %>/images',
                javascriptsDir: '<%= app.assets.src %>/scripts',
                fontsDir: '<%= app.assets.src %>/fonts',
                relativeAssets: true,
                assetCacheBuster: true,
                outputStyle: 'compact',
                lineComments: false,
                raw: 'Sass::Script::Number.precision = 10\n'
            },
            dist: {
                options: {
                    relativeAssets: false,
                    httpImagesPath: 'http://joinblush.com/assets/dist/images',
                    httpGeneratedImagesPath: 'http://joinblush.com/assets/dist/images/generated',
                    httpFontsPath: 'http://joinblush.com/assets/dist/fonts/',
                    httpJavascriptsPath: 'http://joinblush.com/assets/dist/scripts/'
                }
            },
            server: {
                options: {
                    sourcemap: true
                }
            }
        },

        // Copies remaining files to places other tasks can use
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        dot: true,
                        cwd: '<%= app.views.includes %>',
                        dest: '<%= app.views.includes %>/prod',
                        src: [
                            '*.html'
                        ]
                    },
                    {
                        expand: true,
                        cwd: '<%= app.assets.src %>',
                        dest: '<%= app.assets.dist %>',
                        src: [
                            'fonts/{,*/}*.*'
                        ]
                    }
                ]
            }
        },

        // Renames files for browser caching purposes
        filerev: {
            dist: {
                src: [
                    '<%= app.assets.dist %>/scripts/{,*/}*.js',
                    '<%= app.assets.dist %>/stylesheets/{,*/}*.css',
                    '<%= app.assets.dist %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}',
                    '<%= app.assets.dist %>/fonts/*'
                ]
            }
        },

        imagemin: {
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= app.assets.src %>/images',
                        src: '{,*/}*.{png,jpg,jpeg,gif}',
                        dest: '<%= app.assets.dist %>/images'
                    }
                ]
            }
        },

        concat: {
            generated: {
                options: {
                    stripBanners: true
                }
            }
        },

        useminPrepare: {
            html: '<%= app.views.includes %>/{,*/}*.html',
            options: {
                dest: 'www',
                root: 'www',
                flow: {
                    html: {
                        steps: {
                            js: ['concat', 'uglifyjs'],
                            css: ['cssmin']
                        },
                        post: {}
                    }
                }
            }
        },

        usemin: {
            html: ['<%= app.views.includes %>/prod/{,*/}*.html'],
            css: ['<%= app.views.dist %>/stylesheets/{,*/}*.css'],
            options: {
                assetsDirs: [
                    '<%= app.assets.dist %>',
                    '<%= app.assets.dist %>/fonts',
                    '<%= app.assets.dist %>/images',
                    '<%= app.assets.dist %>/stylesheets'
                ]
            }
        },


        // Add vendor prefixed styles
        autoprefixer: {
            options: {
                browsers: ['last 2 versions', 'ie 9', '> 1%']
            },
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= app.assets.src %>/stylesheets/',
                        src: '*.css',
                        dest: '<%= app.assets.src %>/stylesheets/'
                    }
                ]
            }
        },

        cacheBust: {
            options: {
                baseDir: 'www',
                deleteOriginals: true
            },
            assets: {
                files: {
                    src: ['<%= app.views.includes %>/prod/{,*/}*.html']
                }
            }
        },

        // cdn: {
        //     options: {
        //         /** @required - root URL of your CDN (may contains sub-paths as shown below) */
        //         cdn: 'https://joinblush.com',
        //         /** @optional  - if provided both absolute and relative paths will be converted */
        //         flatten: true,
        //         /** @optional  - if provided will be added to the default supporting types */
        //         supportedTypes: { 'phtml': 'html' }
        //     },
        //     dist: {
        //         /** @required  - gets sources here, may be same as dest  */
        //         cwd: '<%= app.views.includes %>/prod/',
        //         /** @required  - puts results here with respect to relative paths  */
        //         dest: '<%= app.views.includes %>/prod/',
        //         /** @required  - files to process */
        //         src: ['{,**/}*.html']
        //     }
        // },

        // cloudfiles: {
        //     dist: {
        //         user: '2bmeprofessor',
        //         key: '8b872a1922df16ba376d2cd02f8944f4',
        //         region: 'IAD',
        //         upload: [
        //             {
        //                 container: 'joinblush',
        //                 src: './www/assets/**/*',
        //                 stripcomponents: 4,
        //                 dest: 'assets/dist/'
        //             }
        //         ]
        //     }
        // },
        setPHPConstant: {
            dist: {
                constant: 'APPVERSION',
                value: new Date().getTime(),
                file: 'www/app/application/version.php'
            }
        }
    });

    grunt.registerTask('build', [
        'setPHPConstant:dist',
        //'clean:dist',
        'useminPrepare',
        'compass:dist',
        //'imagemin:dist',
        'autoprefixer:dist',
        'concat',
        'copy',
        'cssmin',
        'uglify',
        'usemin',
        'cacheBust',
        //'cloudfiles',
        //'cdn'
    ]);

    grunt.registerTask('default', [
        'jshint',
        'build'
    ]);
};