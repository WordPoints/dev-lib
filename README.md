# WordPoints Dev Lib [![Build Status](https://travis-ci.org/WordPoints/dev-lib.svg?branch=master)](https://travis-ci.org/WordPoints/dev-lib)
Developer tools for WordPoints projects.

## Installation

It is intended that this repo be included in a project repo via git-submodule in a
`dev-lib/` directory.

```bash
git submodule add https://github.com/WordPoints/dev-lib.git dev-lib
```

To update:

```bash
git submodule update --remote dev-lib
```

After updating it is recommended that you update your `.travis.yml` file by copying
the corresponding file from this repo (see below).

## Set Up

### Modules

```bash
# Copy the Travis CI configuration file. This is copied rather than symlinked because
# Travis needs to be able to retrieve it from GitHub.
cp dev-lib/travis/module.yml .travis.yml

# Symlink the PHPCS configuration if you want to use PHPCS.
ln -s dev-lib/phpcs/WordPoints/ruleset.xml phpcs.ruleset.xml

# Symlink the Coveralls configuration file if you want to use code coverage.
ln -s dev-lib/travis/.coveralls.yml .
```
