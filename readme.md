<p align="center"><a href="https://formewp.github.io" target="_blank"><img src="https://formewp.github.io/logo.svg" width="400"></a></p>

# Forme CodeGen

Code Generation CLI For The Forme WordPress Framework.

Install it globally.

```sh
composer global require forme/codegen
```

[Click here for Documentation](https://formewp.github.io)

## Tests

```sh
composer test
```

## Output all help as markdown

```bash
# rquires findutils on OSX - you can drop the "g" xargs prefix on Linux
forme list --raw | cut -d' ' -f1 | gxargs -n1 -d'\n' forme --format=md help > codegen.md
```
