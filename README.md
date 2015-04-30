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

After updating it is recommended that you update run the `init` script again, as
shown below.

## Set Up

### Modules

```bash
dev-lib/run init
```

### Other

If your project is not a module, or it is using a different path to the dev-lib, you
will need to first configure the dev-lib by creating a `.wordpoints-dev-lib-config.sh`
file in the root of your project. In it, you can define the project type and/or 
dev-lib path using the `$WORDPOINTS_PROJECT_TYPE` and  `$DEV_LIB_PATH` variables,
respectively.

To define the project type:

```bash
export WORDPOINTS_PROJECT_TYPE=plugin
```

To define the dev-lib path:

```bash
export DEV_LIB_PATH=custom-dev-lib-path
```

From then on, you will be able to run `dev-lib/bin/run init` as shown above to 
initialize and update the dev lib. You will also be able to run the other scripts
included in the dev lib.
