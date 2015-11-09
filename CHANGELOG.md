# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).
And as you can see, we [keep a CHANGELOG](http://keepachangelog.com/).

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

