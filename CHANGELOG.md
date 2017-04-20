# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
And as you can see, we [keep a CHANGELOG](http://keepachangelog.com/).

## [2.6.0] - 2017-04-19
### Added
- WordPress 4.7 to build matrix. #198
- PHPUnit test suite bootstrap from WordPoints. #193
- Points type factory for use in the PHPUnit tests. #200
- Git `pre-commit` hook which automatically checks all staged files for codesniff issues. #20
- Support for the `@requires WordPoints version` and `@WordPoints-version <version>` annotations for the PHPUnit tests, to require a particular WordPoints version for a test.
- Support for the `@WordPoints-requires <callback>` annotation for the PHPUnit tests, to specify a boolean callback that must return a true result for the test to run.
- Minification of CSS, JS, and images to the default Grunt config. #208
- `Generic.Files.OneClassPerFile` and `Generic.Files.OneInterfacePerFile` to PHPCS ruleset.
- Restricted PHPUnit assertions PHPCS sniff, which flags the use of non-strict assertions.
- PHPCS ruleset for the dev-lib itself, which is also now checked via our Travis build.
- Support for running the uninstall tests for a module to only uninstall the module, but not WordPoints, by setting `WORDPOINTS_ONLY_UNINSTALL_MODULE=1`. #192
  - Add these tests to the default Travis build.
- `set-up` command to install dependencies and hook up the pre-commit hook when a user checks out a project using the dev-lib.
  - Also hooks up the pre-commit hook for the dev-lib as well.

### Changed
- String sniffer to ignore all `.lock` files. #199
- XMLLint check to only run if any `.xml` files exist. #201
- The autoloader checker to automatically detect dependencies of an autoloader.
  - If for a module, the classes may be dependent on WordPoints core's main autoloader. #207
  - This in turn means that for modules we can no longer run the check on the codesniff pass on Travis CI, because WordPoints is not installed yet at that point.
  - The `CODESNIFF_PHP_AUTOLOADER_DEPENDENCIES` can be used to specify the default dependencies for a project.
  - WordPoints core's points component's classmaps are automatically assigned as dependencies for module classmaps within a `/points/` directory.
- PHPCS ruleset to exclude the PHP syntax sniff. This is unnecessary since we already do syntax checks.
- PHPCS ruleset to not run the i18n sniff on the tests.
- Code coverage results to be submitted to codecov.io instead of Coveralls. #109
- Env bootstrap to allow `$WP_DEVELOP_DIR` to be preset.
- Env bootstrap to automatically set the WordPoints tests directory based on the develop directory.
- Autoloader validator to automatically load the `WP_Widget` class in case any classes extend it.
- Grunt config to automatically detect the module namespace as the prefix for the classes in the autoloader classmaps.
- Updated default browserify version to 5.0.0.
- Grunt to detect the first open port for livereload when running watch.
- Travis bootstrap to update composer when running on PHP 5.2.
- `makepot` command to automatically create the `languages` directory if needed.
- PHPCS ruleset to disable class filename checks via `WordPress.Files.FileName`.
- PHPCS ruleset to disable errors about associative arrays not being multiline from `WordPress.Arrays.ArrayDeclarationSpacing.AssociativeKeyFound`.
- PHPCS ruleset to use the new method of restricting functions and variables.
- PHPCS ruleset to use the new WordPress filename sniff instead of the Generic one.
- `codesniff-phpcs` command to stop silencing warnings from PHPCS, so that they are shown in addition to errors.
- Travis build to test our PHPCS sniffs.
- Missing Echo PHPCS sniff, adding `$this->single_row_columns()` to the ignored list.
- L10n validator config, adding ignore rules for the points logs widget.
- PHPCS version used, updating it to 2.8.1
- Module tests scaffold to replace the example with the module namespace.
- Travis bootstrap to automatically use PHPUnit 5.7 when running on PHP 7 and PHP nightly.
- PHPCS ruleset to allow `system()` calls in tests.
- WPCS version to 607db751e90e6d32f96fcb15c4aec8609d059d57.
- Travis bootstrap to not run against WordPoints stable and WordPress 4.6 on PHP 7.1.
- Travis bootstrap to recognize that `master` is now the stable WordPoints branch, and `develop` is the development branch.
- Travis bootstrap to use `develop` to denote WordPress trunk, for consistency.
- PHPUnit bootstrap to automatically set up autoloading for a module's PHPUnit helper classes.
- PHPCS Missing Echo sniff to ignore functions with names containing `display`.
- L10n validator config to add `WordPoints_Modules::get_data()` to the ignores.
- L10n validator confit to add `'.min'` to the default ignored strings.
- `init` command to automatically detect the module namespace and set it as the class prefix for the autoloader generator, instead of just basing this of the directory name of the module.

### Deprecated
- `WordPoints_Dev_Lib_PHPUnit_Class_Autoloader` in favor of `WordPoints_PHPUnit_Class_Autoloader`. #193
- `WordPoints_Dev_Lib_PHPUnit_TestCase_Module_Uninstall` in favor of `WordPoints_PHPUnit_TestCase_Module_Uninstall`. #193

### Removed
- Support for specifying autoloader dependencies in the Grunt config file. #207

### Fixed
- The autoloader checker not checking a classmap file in the `src/classes` directory. #206
- Grunt not detecting autoloader errors if they were written to `stdout` instead of `stderr`.
- Fatal error from the makepot class due to the `$max_header_lines` property being private in the parent class.
- PHPCS sniff not correctly flagging `wp_remote_*()` functions and recommending `wp_safe_remote_*()` instead.

## [2.5.0] - 2016-12-09
### Added
- Default config file for Grunt, with a watch task to build the autoload classmaps. (#162)
- `hadActivatedModule()` method to the acceptance tester class. (#164)
- `textContent`, `preserveWhitespace`, and `longOptions` to the list of allowed non-snakecase properties in the PHPCS config. (#165)
- `hadActivatedComponent()` method to the acceptance tester class. (#170)
- Support for sniffing individul files with `codesniff-phpcs`. (#171)
- `haveModuleInstalled()` method to the acceptance tester class. (#173)
- `Element` class for code-reuse in the acceptance tests. (#175)
 - `Reaction` and `ReactionCondition` element classes.
- `cantSeePointsReactionInDB()`, `canSeePointsReactionConditionInDB()`, and `cantSeePointsReactionConditionInDB()` acceptance tester methods.
- Support for running `phpcbf` command of PHPCS via `codesniff-phpcbf`. (#180)
- Entity restriction API functions and component and module apps functions to the l10n validator exclude lists.
- Deprecated action/filter functions to the l10n validator exclude lists.
- Support for placing acceptance tests that need to be run with WordPoints network-active in the `network` subdirectory. (#189)
- Generic string sniffing with `codesniff-strings`, based on `grep`.
 - Flags links with `_blank` targets. (#187)
 - Non-HTTPS links. (#183)
 - Specific strings can be added to an ignore list via `$CODESNIFF_IGNORED_STRINGS`.

### Changed
- PHPUnit autoloader to automatically be register itself when any autoload directories were added. (#160)
- PHPCS config to exclude the `VIP.is_mobile` rule. (#165)
- PHPCS config to allow debugging functions in tests. (#167)
- PHPUnit bootstrap to fully support WPPPB. (#161)
 - Support for `phpunit.uninstall.xml.dist`. (#168)
 - Autoloader is included earlier, before non-dev-lib code.
 - Module is now automatically loaded using WPPPB-like technique.
 - WPPPB is now automatically installed via `composer` on `init`.
 - Adds a module uninstall PHPUnit testcase.
- Codesniffing to ignore the `.idea` directory. (#172)
- Acceptance tests for fully suspend object caching. (#174)
- Acceptance tests to be excluded from snakecase variable name PHPCS snif. (#166)
- PHPCS config to not report formatting errors for long-condition ending comments. (#181)
- Missing echo PHPCS sniff, adding points logs view methods to whitelist.
- PHPCS config to exclude `Generic.Strings.UnnecessaryStringConcat` rule.
- Acceptance tests to support running as multisite. (#138)
- L10n validator config to ignore `class_exists()`.
- `update-version` command to also automatically update the copyright year. (#150)
- PHPUnit bootstrap to automatically load a module's admin-side code. (#195)
- Travis config to run against PHP 7.1. (#194)
- Codesniffing exclude paths to exclude the `node_modules` directory.

### Removed
- Exclusion for `index.php` files from having proper file doc-comments from the PHPCS config.

### Fixed
- `amLoggedInAsAdminOnPage()` method on the acceptance tester class dropping query args from the passed URL. (#163)
- PHPUnit autoloader failing to autoload tests properly when multiple directories were registere for the same prefix. (#177)
- Warnings from `mysql` and `mysqldump` command about passwords on some systems. (#188)
- `update-version` command using too broad a pattern. (#178)
- Codeception tests being run even when there weren't any. (#191)

## [2.4.0] - 2016-08-31
### Added
- Listener for slow PHPUnit tests. (#110)
- Codeception testing support and basic scaffold to be copied over to projects on `init`. (#46)
- Basic `.gitignore` file, which ignores composer and npm artifacts (#122). (#46)
- `bitwise`, `forin`, `freeze`, `laxbreak`, `laxcomma`, `nonbsp` (#148), and `browserify` to the JSHint configuration. (#120)
- `src` directory to the code coverage whitelist in the PHPUnit config. (#123)
- Ignore browserified files when running JSHint. (#119)
- Grunt task to automatically generate PHP class maps for WordPoints's autoloader. (#126)
- `update` command to easily update the library: `dev-lib/run update`. (#118)
- Autoloader for PHPUnit tests and helpers. (#127)
- PHPCS rule to recommend `date()` over `gmdate()`. (#144)
- Ignore rules relating to the new Hooks and Entities APIs to the l10n validator configuration. (#158)

### Changed
- WordPress versions in Travis build matrices to reflect the support for the latest version of WordPoints.
- PHPCS config to allow filesystem writes in tests. (#132)
- `makepot` command to no longer use fuzzy strings. (#140)
- Codesniffing paths to be more granular. (#113)
- `update-version` command to also update the version in `package.json`, if present. (#121)
- Configuration for the l10n validator not to ignore the `$log_text` and `$meta` args for the `wordpoints_*_points()` functions. (#125)
- WPCS version used to 0.10.0. (#112)

### Fixed
- PHP files being executed during the syntax check, resulting in build failures on HHVM. (#114)
- Builds not failing when PHP syntax errors were detected. (#111)
- `$WORDPOINTS_MODULE` env var not being set correctly.
- Bash syntax checks not failing builds for syntax errors. (#152)

## [2.3.1] - 2015-12-19
### Added
- WordPress 4.4 to Travis CI matrixes.
- PHP 7.0 to Travis CI matrixes.
- Support for installing modules that use the installables API.

## [2.3.0] - 2015-11-09
### Added
- `WordPoints.PHPUnit.MissingCovers` sniff to flag tests missing `@covers`
annotations (#74).
- `Squiz.Scope.MethodScope` and `Squiz.WhiteSpace.ScopeKeywordSpacing` sniffs (#101).

### Removed
- `WordPress.VIP.RestrictedFunctions.custom_role` error (#102).
 
### Fixed
- `xmllint` sniffing when multiple XML files are present (#103).

## [2.2.0] - 2015-10-30
### Added
- `WordPoints.PHP.RequiredParentMethodCall` sniff to flag missing calls to
`parent::setUp()`, etc. (#75).
- `WordPoints_Modules::register()` and `WordPoints_Widget::get_field_name()`
to the l10n validator ignores (#85 and #92).
- `do_meta_boxes()`, `settings_errors()` and `$this->display_content` to the list of
output functions in the `WordPoints.PHP.MissingEcho` sniff (#91).
- `esc_sql()` to the list of disallowed functions in the
`WordPoints.PHP.DisallowedFunctions` sniff (#90).
- Exclusion for test files for the `WordPress.WP.PreparedSQL` sniff (#84).
- WordPress 4.3 to the Travis CI config for PHPUnit tests.
- `esc_js()` to the list of disallowed functions in the
`WordPoints.PHP.DisallowedFunctions` sniff (#98).

### Changed
- `makepot` to inclue fuzzy strings in `.mo` files (#83).
- Default PHPCS version to `4122da6604e2967c257d6c81151122d08cae60cf` (#95).
- Default WPCS version to `a54499411fb9ca55a35fc7003422868cdd072ef2` (#96).

### Removed
- `boss` from jshint config (#93).

## [2.1.1] - 2015-07-22
### Changed
- WPCS version to latest commit on `develop` (0.6.0).

### Fixed
- Latest development version of the WP L10n Validator not being used. #80

## [2.1.0] - 2015-06-27
### Added
- `WordPoints.PHP.DisallowedFunctions` sniff to flag usage of unserializing functions 
(#76), non-`safe` `wp_remote_*()` functions (#73), and non-`safe` `wp_redirect()` 
(#67).

### Fixed
- Local `WP_TESTS_DIR` value being overwritten when running tests or `makepot`.

## [2.0.4] - 2015-06-06
### Added
- `customUnslashingSanitizingFunctions` property configuration for the 
`WordPress.VIP.ValidatedSanitizedInput` sniff. #71

### Changed
- WPCS version to latest commit on `develop`. #72
- Enabled the `WordPress.VIP.DirectDatabaseQuery` sniff. #72

## [2.0.3] - 2015-05-23
### Added
- `WordPress-Extra` and `WordPress-Docs` to the PHPCS ruleset. #69
- `WordPoints_installables::*` and `WordPoints_Un_Installer_Base::map_shortcuts` to 
the l10n validator ignores list. #68

### Changed
- WPCS version to latest commit on `develop`. #69

## [2.0.2] - 2015-05-01
### Fixed
- Failure of broken symlink sniff even when there were no broken symlinks. #66

## [2.0.1] - 2015-05-01
### Changed
- WPCS version to latest commit on `develop`. #63

### Fixed
- The `init` command not symlinking the `.jshintignore` file. #65
- The `init` command not showing deprecated warning for `.ci-env.sh` file. #64

## [2.0.0] - 2015-05-01
### Added
- This change log. #61
- WordPress 4.2 to the Travis CI build matrix. #58
- Configuration for jshint. #56
- Sniff for broken symlinks. #54
- Tests to ensure the lib is compatible with PHP nightly and HHVM. #53
- Support for a `.wordpoints-dev-lib-config` configuration file. #50, #47
- Support for a `wordpoints-dev-lib-config` bash command to override vars and 
functions. #50
- `WordPoints_Un_Installer_Base::map_uninstall_shortcut`, 
`WordPoints_Un_Installer_Base::uninstall_metadata`, 
`WordPoints_Un_Installer_Base::uninstall_`, and 
`WordPoints_Un_Installer_Base::maybe_update_tables_to_utf8mb4` to the list of 
ignored functions in the l10n validator config.

### Changed
- Travis CI build setup to use the `--prefer-source` option when installing composer 
dependencies. #59
- PHPUnit bootstrap to use the exit code 1 when `WP_TESTS_DIR` isn't set. #55

### Deprecated
- Support for the `.ci-env` configuration file. #50

### Fixed
- Build errors on Travis CI from WordPress not being installed correctly. #57

## [1.3.3] - 2015-04-03
### Fixed
- `bin/init.sh` creating symlinks with absolute paths. #51

## [1.3.2] - 2015-03-05
### Fixed
- `makepot.sh` not working for modules. #39
- `init.sh` not working with WordPoints. #40

## [1.3.1] - 2015-02-28
### Changed
- WPCS version. #37

### Fixed
- Code coverage build for projects with no composer.json. #36

## [1.3.0] - 2015-02-27
### Added
- Script to update a project's version. #29
- Script to generate and update POT and PO/MO files. #28
- `wordpoints_sanitize_wp_error()` to the list of sanitizing functions for the XSS 
PHPCS sniff. #33
- PHP nightly to the Travis CI build. #32

### Changed
- The default location of the l10n validator's cache files to be in the project root. #31

### Removed
- WordPress 3.8 from the Travis CI build. #34

### Fixed
- init.sh script failing when multiple .php files are in the src/ directory. #30

## [1.2.0] 2015-02-25
### Added
- Setup script. #24

### Fixed
- L10n Validator not ignoring wordpoints_modules_ur(). #26

## [1.1.0] - 2015-02-20
### Added
- Added PHPUnit bootstrap for modules. #19, #22
- L10n validator config is now included with this repo, and the l10n validator is 
automatically installed if there is a config file for it. #23

### Changed
- Composer dependencies are no longer installed for the `codesniff` pass. #23

### Fixed
- `dev-lib` path not being excluded from codesniffing by default. #21

## [1.0.0] - 2015-02-17
### Added
- Initial code.

[Unreleased]: https://github.com/WordPoints/dev-lib/compare/master...develop
[2.6.0]: https://github.com/WordPoints/dev-lib/compare/2.5.0...2.6.0
[2.5.0]: https://github.com/WordPoints/dev-lib/compare/2.4.0...2.5.0
[2.4.0]: https://github.com/WordPoints/dev-lib/compare/2.3.1...2.4.0
[2.3.1]: https://github.com/WordPoints/dev-lib/compare/2.3.0...2.3.1
[2.3.0]: https://github.com/WordPoints/dev-lib/compare/2.2.0...2.3.0
[2.2.0]: https://github.com/WordPoints/dev-lib/compare/2.1.1...2.2.0
[2.1.1]: https://github.com/WordPoints/dev-lib/compare/2.1.0...2.1.1
[2.1.0]: https://github.com/WordPoints/dev-lib/compare/2.0.4...2.1.0
[2.0.4]: https://github.com/WordPoints/dev-lib/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/WordPoints/dev-lib/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/WordPoints/dev-lib/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/WordPoints/dev-lib/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/WordPoints/dev-lib/compare/1.3.3...2.0.0
[1.3.3]: https://github.com/WordPoints/dev-lib/compare/1.3.2...1.3.3
[1.3.2]: https://github.com/WordPoints/dev-lib/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/WordPoints/dev-lib/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/WordPoints/dev-lib/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/WordPoints/dev-lib/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/WordPoints/dev-lib/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/WordPoints/dev-lib/compare/...1.0.0

