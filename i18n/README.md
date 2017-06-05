# I18n (internationalization) tools

## POT Generation

The `makepot` script will generate or refresh your extension's POT file. It will also
refresh any PO files, and regenerate the MO files, too. To use it, all you need to do
is run this from your project's root:

```bash
dev-lib/run makepot
```

The textdomain for your project will automatically be determined from the `Text 
Domain:` header in your project's main file. If this fails, it will ask you to enter
the textdomain.

The POT and other language files are assumed to reside in the `src/languages` 
directory, and it's assumed that your POT file is named `textdomain.pot`, where
`textdomain` is your project's textdomain.
