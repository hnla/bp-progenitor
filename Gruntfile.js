/* jshint node:true */
/* global module */
module.exports = function(grunt) {
	var WORKING_DIR = 'community/',
					MAIN_WORKING_DIR = 'assets/',

		BP_NTP_CSS = [
			'**/*.css'
		],

		WP_NTP_CSS = [
			'css/*.css'
		],

		BP_NTP_EXCLUDED_CSS = [
			'!**/*-rtl.css',
			'!**/*.min.css'
		],

		WP_NTP_EXCLUDED_CSS = [
			'!css/*-rtl.css',
			'!css/*.min.css'
		],

		BP_NTP_JS = [
			'**/*.js',
			'!**/*.min.js'
		],

		stylelintConfigCss  = require('stylelint-config-wordpress/index.js'),
		stylelintConfigScss = require('stylelint-config-wordpress/scss.js');

	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

	grunt.initConfig( {
		pkg: grunt.file.readJSON('package.json'),

		checkDependencies: {
			options: {
				packageManager: 'npm'
			},
			src: {}
		},
		jshint: {
			options: grunt.file.readJSON( '.jshintrc' ),
			grunt: {
				src: ['Gruntfile.js']
			},
			core: {
				expand: true,
				cwd: WORKING_DIR,
				src: BP_NTP_JS
			}
		},
		rtlcss: {
			options: {
				opts: {
					processUrls: false,
					autoRename: false,
					clean: true
				},
				saveUnmodified: false
			},
			buildrtlbp: {
				expand: true,
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				extDot: 'last',
				ext: '-rtl.css',
				src: BP_NTP_CSS.concat( BP_NTP_EXCLUDED_CSS )
			},
			buildrtlwp: {
				expand: true,
				cwd: MAIN_WORKING_DIR,
				dest: MAIN_WORKING_DIR,
				extDot: 'last',
				ext: '-rtl.css',
				src: WP_NTP_CSS.concat( WP_NTP_EXCLUDED_CSS )
			}
		},
		sass: {

			options: {
				outputStyle: 'expanded',
				unixNewlines: true,
				indentType: 'tab',
				indentWidth: '1',
				indentedSyntax: false
			},
			bp: {
			cwd: WORKING_DIR,
			extDot: 'last',
			expand: true,
			ext: '.css',
			flatten: true,
			src: ['sass/buddypress.scss'],
			dest: WORKING_DIR + 'css/'
		},
		wp: {
				cwd: MAIN_WORKING_DIR,
				extDot: 'last',
				expand: true,
				ext: '.css',
				flatten: true,
				src: [
					'sass/core-style.scss',
					'sass/site-layout.scss',
					'sass/appearence.scss',
					'sass/font-awesome/font-awesome.scss'
					],
				dest: MAIN_WORKING_DIR + 'css/'
			}
		},
		stylelint: {
			css: {
				options: {
					config: stylelintConfigCss,
					format: 'css'
				},
				expand: true,
				cwd: WORKING_DIR,
				src: [
					'css/*.css',
					'!css/buddypress.css',
					'!css/buddypress-rtl.css',
					'!css/*.min.css'
				]
			//ignoreFiles: 'bp-nouveau/css/*-rtl.css'
			},
			wpscss: {
				options: {
					config: stylelintConfigScss,
					format: 'scss'
				},
				expand: true,
				cwd: MAIN_WORKING_DIR,
				src: [
					'sass/*.scss',
					'sass/*/*.scss',
					'sass/*/*/*.scss',
					'!sass/*/_embedded-fonts.scss',
					'!sass/font-awesome/*.scss'
				]
			},
			scss: {
				options: {
					config: stylelintConfigScss,
					format: 'scss'
				},
				expand: true,
				cwd: WORKING_DIR,
				src: [
					'sass/*.scss',
					'common-styles/*.scss',
					'sass/*.scss'
				]
			}
		},
		cssmin: {
			minify: {
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				extDot: 'last',
				expand: true,
				ext: '.min.css',
				src: [
					'css/*.css',
					'!css/*.min.css'
				]
			}
		},
		uglify: {
			core: {
				cwd: WORKING_DIR,
				dest: WORKING_DIR,
				extDot: 'last',
				expand: true,
				ext: '.min.js',
				src: [
					'js/*.js',
					'!js/*.min.js'
				]
			}
		},
		watch: {
			config: {
				files: 'Gruntfile.js',
				tasks: 'jshint:grunt'
			},
			bp: {
				files: [
					'community/sass/buddypress.scss',
					'community/common-styles/*.scss',
					'community/sass/*.scss',
					'assets/sass/*.scss',
					'assets/sass/*/*.scss',
					'assets/sass/*/*/*.scss'
					],
				tasks: 'sass'
			}
		},
		phpunit: {
			'default': {
				cmd: 'phpunit',
				args: ['-c', 'phpunit.xml.dist']
			},
			'multisite': {
				cmd: 'phpunit',
				args: ['-c', 'tests/phpunit/multisite.xml']
			},
			'ajax': {
				cmd: 'phpunit',
				args: ['-c', 'phpunit.xml.dist', '--group', 'ajax']
			}
		}
	});

	// Lint CSS & JavaScript
	grunt.registerTask( 'lint', ['stylelint', 'jshint' ] );

	// Build CSS & JavaScript
	grunt.registerTask( 'build', [ 'rtlcss', 'cssmin',  'uglify' ] );

	// Default task(s).
	grunt.registerTask( 'default', 'Runs the default Grunt tasks', [ 'checkDependencies', 'lint', 'build' ] );

	// Testing tasks.
	grunt.registerMultiTask( 'phpunit', 'Runs PHPUnit tests, including the ajax and multisite tests.', function() {
		grunt.util.spawn( {
			args: this.data.args,
			cmd:  this.data.cmd,
			opts: { stdio: 'inherit' }
		}, this.async() );
	});

	// Travis CI Tasks.
	grunt.registerTask( 'travis:grunt', 'Runs the Grunt build tasks.', [ 'lint', 'build' ] );
	grunt.registerTask( 'travis:phpunit', 'Runs the PHPUnit tasks.',[ 'default', 'phpunit:default', 'phpunit:multisite', 'phpunit:ajax' ] );
};
