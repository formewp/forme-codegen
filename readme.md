<p align="center"><a href="https://formewp.github.io" target="_blank"><img src="https://formewp.github.io/logo.svg" width="400"></a></p>

# Forme CodeGen

Code Generation CLI For The Forme WordPress Framework.

Install it globally.

```sh
composer global require forme/codegen
```

[Click here for Documentation](https://formewp.github.io)

## Development

For development run `phive install --force-accept-unsigned` followed by `composer install`.

Tools are in `./tools` rather than `./vendor/bin`

You also need [git cliff](https://github.com/orhun/git-cliff).

The useful ones are set up as composer scripts.

```sh
composer test # run pest
composer stan # run phpstan on src
composer rector:check # rector dry run on src
composer rector:fix # rector on src
composer cs:check # php cs fixer dry run on src
composer cs:fix # php cs fixer on src
composer phar:build # build phar with box (experimental, some features don't work yet)
composer changelog # run git cliff
composer hooks # install git hooks (will run on composer install automatically)
composer bump # bump to the next patch version - can also take argument "minor" or "major"
```

## Output all help as markdown

```bash
# requires findutils on OSX - you can drop the "g" xargs prefix on Linux
forme list --raw | cut -d' ' -f1 | gxargs -n1 -d'\n' forme --format=md help | sed $'s/\x1b\\[91m\([A-Za-z\\._-]*\)\x1b\\[0m/`\\1`/g' | sed $'s/    \x1b\\[0\;33m\(.*\)\x1b\\[0m/```bash\\n\\1\\n```/g' | sed $'s,\x1b\\[[0-9;]*[a-zA-Z],,g' > codegen.md
```

Manual steps:
1. Check the section/command titles as this doesn't seem to output correct line breaks
2. Find and replace home folder
3. Copy into docs
