Generates a new forme boilerplate plugin or theme project.

You need to pass in the `type` of project you want to generate and a `name`.

```bash
# make a new plugin project
forme new plugin FooBar

# make a new theme project
forme new theme FooBar
```

This will create a new directory with all the necessary boiler plate code and initialise the git repo for you.

As a default the cli will put the project into the generic `App` vendor namespace. If you need something else, you can use the `--vendor` option.

```bash
forme new plugin FooBar --vendor=BazQux
```

You might have different Github users (e.g a work and personal one) and might have assigned different host aliases to them in your git config. If you need to pass a host in to access an alternative github account, you can use the `--host` option.

```bash
forme new plugin FooBar --host=work
```
