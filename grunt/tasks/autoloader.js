/**
 * Grunt plugin for generating PHP autoload files for WordPoints's autoloader.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.4.0
 */

var spawnSync;

module.exports = function ( grunt ) {

	grunt.registerMultiTask( 'autoloader', 'Generate the class map files for WordPoints\'s PHP autoloader', function() {

		var src_dir = this.data.src_dir || 'src/',
		 	failures = false,
			class_dirs = grunt.file.expand(
				{ cwd: src_dir }
				, [ '**/classes' ]
			);

		for ( var i = 0; i < class_dirs.length; i++ ) {
			if (
				! generate_autoloader_file(
					src_dir + class_dirs[ i ] + '/'
					, this.data.filter
					, this.data.prefix
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
	 * Generates a class map file for the WordPoints autoloader, and verifies that
	 * it will work as a fallback when SPL autoloading is disabled. A failure
	 * usually indicates that the classes need to be sorted so that they load in a
	 * different order when SPL is disabled.
	 *
	 * @param classes_dir {string}      The path to the class directory to generate
	 *                                  the autoloader file for.
	 * @param filter      {func}        A function to pass the list of files to for
	 *                                  filtering and sorting. Note that only class
	 *                                  files are passed, not interface files. The
	 *                                  class directory is passed as the second
	 *                                  parameter.
	 * @param prefix      {string|func} A prefix for the classes in this directory,
	 *                                  or a callback function to get the prefix. The
	 *                                  callback will be passed the class directory.
	 *
	 * @returns {boolean} Whether the autoloader file was generated successfully.
	 */
	function generate_autoloader_file( classes_dir, filter, prefix ) {

		var includes = '',
			contents,
			class_name,
			error,
			result,
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

		// Prepare the class map.
		if ( typeof prefix === 'function' ) {
			prefix = prefix( classes_dir );
		}

		for ( i = 0; i < class_files.length; i++ ) {

			class_name = class_files[ i ].substr( 0, class_files[ i ].length - 4 /* .php */ );
			class_name = class_name.replace( /\//g, '_' );

			includes += '\t\'' + prefix + class_name + '\' => \''
				+ class_files[ i ] + '\',\n';
		}

		if ( grunt.file.exists( file ) ) {
			contents = grunt.file.read( file );
		} else {
			contents = '<?php\nreturn array(\n\t// auto-generated {}\n);\n';
		}

		contents = contents.replace(
			/auto-generated \{[^}]*}/,
			'auto-generated {\n' + includes + '\t// }'
		);

		grunt.file.write( file, contents );

		// Test that the file executes properly.
		if ( ! spawnSync ) {
			spawnSync = require( 'child_process' ).spawnSync;
		}

		result = spawnSync(
			__dirname + '/../../bin/verify-php-autoloader.sh'
			, [ classes_dir ]
		);

		if ( result.error ) {
			error = result.error.message;
		} else if ( result.stderr && result.stderr.length ) {
			error = result.stderr.toString();
		}

		if ( error ) {

			grunt.log.error(
				'Error testing autoload file ' + file + ':\n' + error
			);

			return false;

		} else {

			grunt.log.ok( 'Updated autoload file ' + file );

			return true;
		}
	}
};

// EOF
