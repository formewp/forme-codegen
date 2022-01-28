# 👊 bump

You can use `bump` to increase plugin or theme version numbers according to [semver](https://semver.org) principles.

This convenience command will bump your semver version tag, update the version number in your plugin's main file or theme `style.css`, render a new changelog (requires [git cliff](https://github.com/orhun/git-cliff)), commit and push for you all in one line.

Your version number should be in the format `v0.0.0`.

You can pass in the scope, i.e. `major`, `minor` or `patch` - defaults to `patch`.

```bash
# before e.g. v1.0.1
forme bump
# after v1.0.2

# before e.g. v1.0.2
forme bump minor
# after v1.1.0

# before e.g. v.1.10
forme bump major
# after v2.0.0
```
