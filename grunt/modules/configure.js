/**
 * Default Grunt configuration.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.6.0
 */

/* jshint node:true */
module.exports = function( grunt, dir ) {

	var SOURCE_DIR = 'src/',
		DEVELOP_DIR = './',
		ASSETS_DIR = 'assets/',
		UNBUILT_DIR = 'unbuilt/',
		DEV_LIB_DIR = 'dev-lib/',
		autoprefixer = require( 'autoprefixer' ),
		browserifyConfig = { options: { browserifyOptions: { debug: true } } },
		jsManifests = grunt.file.expand( { cwd: UNBUILT_DIR }, ['**/*.manifest.js'] );

	// Load tasks.
	require( 'matchdep' )
		.filterDev( 'grunt-*', dir + '/package.json' )
		.forEach( grunt.loadNpmTasks );

	grunt.loadTasks( DEV_LIB_DIR + 'grunt/tasks/' );

	// Set up broserify config.
	jsManifests.forEach( function ( manifest ) {

		var build;

		// The compiled files are called *.manifested.js.
		build = manifest.substring( 0, manifest.length - 12 /* .manifest.js */ );

		browserifyConfig[ build ] = { files : {} };
		browserifyConfig[ build ].files[ SOURCE_DIR + build + '.manifested.js' ] = [ UNBUILT_DIR + manifest ];
	} );

	// Project configuration.
	grunt.initConfig({
		autoloader: {
			all: {
				src_dir: SOURCE_DIR,
				prefix:  function ( class_dir ) {

					var prefix = 'wordpoints_',
						basename = require( 'path' ).basename( process.cwd() );

					if ( 'wordpoints' !== basename ) {
						var data = require( '@martin-pettersson/wp-get-file-data' )
							.getFileDataSync( 'src/' + basename + '.php' );

						if ( data.Namespace ) {
							prefix += data.Namespace.toLowerCase() + '_';
						}
					}

					switch ( class_dir ) {

						case 'src/admin/classes/':
							prefix += 'admin_';
							break;

						case 'src/components/points/classes/':
							prefix += 'points_';
							break;

						case 'src/components/points/admin/classes/':
							prefix += 'points_admin_';
							break;
					}

					return prefix;
				}
			}
		},
		browserify: browserifyConfig,
		cssmin: {
			options: {
				compatibility: 'ie7'
			},
			all: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: SOURCE_DIR,
				ext: '.min.css',
				src: [ '**/*.css', '!**/*.min.css' ]
			}
		},
		imagemin: {
			all: {
				expand: true,
				cwd: DEVELOP_DIR,
				src: [
					ASSETS_DIR + '**/*.{gif,jpeg,jpg,png}',
					SOURCE_DIR + '**/*.{gif,jpeg,jpg,png}'
				],
				dest: DEVELOP_DIR
			}
		},
		jsvalidate:{
			options: {
				globals: {},
				esprimaOptions:{},
				verbose: false
			},
			all: {
				files: {
					src: [ SOURCE_DIR + '**/*.js' ]
				}
			}
		},
		postcss: {
			options: {
				processors: [
					autoprefixer({
						browsers: [
							'Android >= 2.1',
							'Chrome >= 21',
							'Edge >= 12',
							'Explorer >= 7',
							'Firefox >= 17',
							'Opera >= 12.1',
							'Safari >= 6.0'
						],
						cascade: false
					})
				]
			},
			all: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: SOURCE_DIR,
				src: [ '**/*.css', '!**/*.min.css' ]
			}
		},
		rtlcss: {
			options: {
				// rtlcss options
				opts: {
					clean: false,
					processUrls: { atrule: true, decl: false },
					stringMap: [
						{
							name: 'import-rtl-stylesheet',
							priority: 10,
							exclusive: true,
							search: [ '.css' ],
							replace: [ '-rtl.css' ],
							options: {
								scope: 'url',
								ignoreCase: false
							}
						}
					]
				},
				saveUnmodified: false,
				plugins: [
					{
						name: 'swap-dashicons-left-right-arrows',
						priority: 10,
						directives: {
							control: {},
							value: []
						},
						processors: [
							{
								expr: /content/im,
								action: function( prop, value ) {
									if ( value === '"\\f141"' ) { // dashicons-arrow-left
										value = '"\\f139"';
									} else if ( value === '"\\f340"' ) { // dashicons-arrow-left-alt
										value = '"\\f344"';
									} else if ( value === '"\\f341"' ) { // dashicons-arrow-left-alt2
										value = '"\\f345"';
									} else if ( value === '"\\f139"' ) { // dashicons-arrow-right
										value = '"\\f141"';
									} else if ( value === '"\\f344"' ) { // dashicons-arrow-right-alt
										value = '"\\f340"';
									} else if ( value === '"\\f345"' ) { // dashicons-arrow-right-alt2
										value = '"\\f341"';
									}
									return { prop: prop, value: value };
								}
							}
						]
					}
				]
			},
			all: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: SOURCE_DIR,
				ext: '-rtl.css',
				src: [ '**/*.css', '!**/*-rtl.css', '!**/*.min.css' ]
			}
		},
		sass: {
			all: {
				expand: true,
				cwd: UNBUILT_DIR,
				dest: SOURCE_DIR,
				ext: '.css',
				src: ['**/*.scss'],
				options: {
					outputStyle: 'expanded'
				}
			}
		},
		uglify: {
			options: {
				ASCIIOnly: true,
				screwIE8: false
			},
			all: {
				expand: true,
				cwd: SOURCE_DIR,
				dest: SOURCE_DIR,
				ext: '.min.js',
				src: [ '**/*.js', '!**/*.min.js' ]
			}
		},
		watch: {
			autoloader: {
				files: [
					SOURCE_DIR + '**/classes/**/*.php',
					'!' + SOURCE_DIR + '**/classes/index.php'
				],
				tasks: ['autoloader'],
				options: {
					event: [ 'added', 'deleted' ]
				}
			},
			browserify: {
				files: [ UNBUILT_DIR + '**/*.js' ],
				tasks: ['browserify'],
				spawn: false
			},
			// This triggers an automatic reload of the `watch` task.
			config: {
				files: 'Gruntfile.js',
				tasks: ['build']
			},
			css: {
				files: [ SOURCE_DIR + '**/*.css' ],
				tasks: [
					'newer:rtlcss:all',
					'newer:postcss:all',
					'newer:cssmin:all'
				]
			},
			imagemin: {
				files: [
					ASSETS_DIR + '**/*.{gif,jpeg,jpg,png}',
					SOURCE_DIR + '**/*.{gif,jpeg,jpg,png}'
				],
				tasks: ['newer:imagemin:all']
			},
			js: {
				files: [ SOURCE_DIR + '**/*.js' ],
				tasks: [ 'newer:uglify:all', 'newer:jsvalidate:all' ]
			},
			livereload: {
				options: { livereload: true },
				files: [ SOURCE_DIR + '/**/*' ]
			},
			sass: {
				files: [ UNBUILT_DIR + '**/*.scss' ],
				tasks: ['newer:sass:all']
			}
		}
	});

	grunt.registerTask(
		'build'
		, [
			// PHP
			'autoloader',

			// JS
			'browserify',
			'uglify:all',
			'jsvalidate:all',

			// CSS
			'sass:all',
			'rtlcss:all',
			'postcss:all',
			'cssmin:all',

			// Images
			'imagemin:all'
		]
	);

	grunt.registerTask( 'default', 'build' );

	// Make sure that we use an open port, in case there are multiple instances of
	// livereload running.
	grunt.task.run( 'openport:watch.livereload.options.livereload:35729' );
};

// EOF
