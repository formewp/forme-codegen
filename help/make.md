#### 🧰 make

Generates class and other boilerplate in the current working directory.

You need to pass in the `type` of boilerplate you want to generate and a `name`.

Available types are `action`, `filter`, `field`, `field-enum`, `controller`, `template-controller`, `registry`, `post-type`, `model`, `transformer`, `service`, `migration`, `job` and `middleware`

For classes you should supply the `name` in PascalCase, for example:

```bash
forme make controller FooBar
```

For hooks and post types, the `name` should be in snake_case.

```bash
forme make action wp_loaded
```

Hooks and field enums will ask you for further configuration via an interactive cli.
