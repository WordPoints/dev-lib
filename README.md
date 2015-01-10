# WordPoints Dev Lib
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

After updating it is recommended that you update your `.travis.yml` file by repeating
the setup steps below.

## Set Up

### Modules

```bash
cp dev-lib/travis/modules.yml .travis.yml
```
