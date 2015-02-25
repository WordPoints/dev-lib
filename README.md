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

After updating it is recommended that you update run the `init.sh` script again, as
shown below.

## Set Up

### Modules

```bash
dev-lib/bin/init.sh
```
