/**
 * Grunt plugin for generating PHP autoload files for WordPoints's autoloader.
 *
 * @package WordPoints_Dev_Lib
 * @since 2.4.0
 */

module.exports = function ( grunt ) {

	grunt.registerMultiTask( 'autoloader', 'Generate the autoloader backup file.', function() {

		var src_dir = this.data.src_dir || 'src/',
			before = 'require_once( $dir . \'/',
			after = '\' );\n',
			includes = '$dir = dirname( __FILE__ );\n',
			contents,
			interfaces = [],
			classes_dir = src_dir + 'includes/classes/',
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
		if ( this.data.filter ) {
			class_files = this.data.filter( class_files );
		}

		// We load interfaces first.
		class_files = interfaces.concat( class_files );

		// Implode all of the class files.
		includes += before;
		includes += class_files.join( after + before );
		includes += after;

		contents = grunt.file.read( file );
		contents = contents.replace(
			/auto-generated \{[^}]*}/,
			'auto-generated {\n' + includes + '// }'
		);

		grunt.file.write( file, contents );

		// Test that the file executes properly.
		var result = require( 'child_process' ).spawnSync( 'php', [ '-e', file ] );

		if ( result.stderr && result.stderr.length ) {

			grunt.log.error(
				'Error executing autoload file:\n' + result.stderr.toString()
			);

			return false;

		} else {
			grunt.log.ok( 'Updated autoload file ' + file );
		}
	});
};

// EOF
