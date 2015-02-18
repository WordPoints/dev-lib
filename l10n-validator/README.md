# L10n Validator Config

WordPoints-specific configuration for [the WP L10n Validator]
(https://github.com/JDGrimes/wp-l10n-validator).

## Usage

Load these ignore rules using the `bootstrap` option in your JSON config file for the
validator:

```json
{
	...
	"bootstrap": "dev-lib/l10n-validator/bootstrap.php"
	...
}
```

You can set up a basic config for the L10n validator by copying the [example JSON
config file](example-config.json), and changing the `textdomain` to match your project:

```bash
cp dev-lib/l10n-validator/example-config.json wp-l10n-validator.json
```

This will configure the validator with the same rules used for the WordPoints plugin.
This includes ignoring some PHP predefined and WordPress functions that you might not
always want to ignore, depending on your project. To only use the ignore rules for
functions defined by WordPoints itself, use the `wordpoints.php` file instead.

See here for [more information on the other configuration options]
(https://github.com/JDGrimes/wp-l10n-validator#configuration) for the validator.