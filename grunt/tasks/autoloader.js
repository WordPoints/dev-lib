/**
 * Grunt plugin for generating PHP autoload files for WordPoints's autoloader.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.4.0
 */

var spawnSync;

module.exports = function ( grunt ) {

	grunt.registerMultiTask( 'autoloader', 'Generate the autoloader backup file.', function() {

		var src_dir = this.data.src_dir || 'src/',
		 	failures = false,
			class_dirs = grunt.file.expand(
				{ cwd: src_dir }
				, [ '**/includes/classes' ]
			);

		for ( var i = 0; i < class_dirs.length; i++ ) {
			if (
				! generate_autoloader_file(
					src_dir + class_dirs[ i ] + '/'
					, this.data.filter
				)
			) {
				failures = true;
			}
		}

		return ! failures;
	});

	/**
	 * Generate the autoloader file for a directory.
	 *
	 * Generates a fallback file for the WordPoints autoloader, and checks that it
	 * can successfully execute. A failure to execute successfully generally
	 * indicates that the classes need to be sorted so that they load in a different
	 * order.
	 *
	 * @param classes_dir {string} The path to the class directory to generate the
	 *                             autoloader file for.
	 * @param filter      {func}   A function to pass the list of files to for
	 *                             filtering and sorting. Note that only class files
	 *                             are passed, not interface files.
	 *
	 * @returns {boolean} Whether the autoloader file was generated successfully. A
	 *                    false result indicates that the generated file could not be
	 *                    successfully executed by PHP.
	 */
	function generate_autoloader_file( classes_dir, filter ) {

		var before = 'require_once( $dir . \'/',
			after = '\' );\n',
			includes = '$dir = dirname( __FILE__ );\n',
			contents,
			interfaces = [],
			file = classes_dir + 'index.php',
			class_files = grunt.file.expand(
				{ cwd: classes_dir },
				[ '**/*.php', '!index.php' ]
			);

		// Extract the interface files. To do that we need to loop backwards so that
		// we can remove elements from the array as we go.
		for ( var i = class_files.length - 1; i >= 0; i-- ) {

			// Our interface file names are currently like interfacei.php.
			if ( class_files[ i ].substr( -5 ) === 'i.php' ) {

				interfaces.push( class_files[ i ] );
				class_files.splice( i, 1 );

			}
		}

		// Allow the class files to be sorted by a custom function.
		if ( filter ) {
			class_files = filter( class_files, classes_dir );
		}

		// We load interfaces first.
		class_files = interfaces.concat( class_files );

		// Implode all of the class files.
		if ( class_files ) {
			includes += before;
			includes += class_files.join( after + before );
			includes += after;
		}

		if ( grunt.file.exists( file ) ) {
			contents = grunt.file.read( file );
		} else {
			contents = '<?php\n// auto-generated {}\n';
		}

		contents = contents.replace(
			/auto-generated \{[^}]*}/,
			'auto-generated {\n' + includes + '// }'
		);

		grunt.file.write( file, contents );

		// Test that the file executes properly.
		if ( ! spawnSync ) {
			spawnSync = require( 'child_process' ).spawnSync;
		}

		var args = [ file ];

		// Some files assume that others will already be loaded.
		if ( 'src/includes/classes' !== classes_dir ) {
			args = [ '-B', 'require("src/includes/classes/index.php");', '-F', file, '--' ];

			if (
				'src/admin/includes/classes' !== classes_dir
				&& -1 !== classes_dir.indexOf( 'admin' )
			) {
				args[1] += 'require("src/admin/includes/classes/index.php");';
			}
		}

		var result = spawnSync( 'php', args,  { input: '\n' } );

		if ( result.stderr && result.stderr.length ) {

			grunt.log.error(
				'Error executing autoload file ' + file + ':\n'
					+ result.stderr.toString()
			);

			return false;

		} else {

			grunt.log.ok( 'Updated autoload file ' + file );

			return true;
		}
	}
};

// EOF
