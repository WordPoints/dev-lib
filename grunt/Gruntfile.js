/**
 * Grunt configuration file.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.5.0
 */

/* jshint node:true */
module.exports = function( grunt ) {

	var SOURCE_DIR = 'src/',
		DEV_LIB_DIR = 'dev-lib/',
		WORDPOINTS_DIR = process.env.WORDPOINTS_DIR;

	// Load tasks.
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );
	grunt.loadTasks( DEV_LIB_DIR + 'grunt/tasks/' );

	// Project configuration.
	grunt.initConfig({
		autoloader: {
			all: {
				src_dir: SOURCE_DIR,
				dependencies: [ WORDPOINTS_DIR + 'src/classes/' ],
				prefix: '%class_prefix%'
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
			// This triggers an automatic reload of the `watch` task.
			config: {
				files: 'Gruntfile.js',
				tasks: ['build']
			}
		}
	});

	grunt.registerTask( 'build', ['autoloader'] );
	grunt.registerTask( 'default', 'build' );
};

// EOF
