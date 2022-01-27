You can use `base` to set up your base WordPress installation.

The available commands are `new`, `link`, `install`, `config`, `autoload`, `dotenv` and `setup`.

Apart from `new`, these all need to be run from within your WordPress base installation directory.

You'll need to have [wp-cli](https://wp-cli.org/) installed and available globally.

```bash
forme base new project-name
```

This will setup a fresh WordPress installation in the `project-name` directory. Make sure you have your DB set up and creds ready before running this command.

```bash
forme base link /path/to/your/plugin/or/theme/repo
```

This will create a symlink to your plugin or theme's repo from the current base installation.

```bash
forme base install
```

Installs the latest version of WordPress into the `public` folder.

```bash
forme base config
```

Initialises `wp-config.php`, including adding the `FORME_PRIVATE_ROOT` const, setting `WP_ENV` to development and punching in DB creds (you'll need those before running this)

```bash
forme base autoload
```

Adds the autoload require statament into `wp-config.php`

```bash
forme base dotenv
```

Simply copies `.env.example` to `.env`

```bash
forme base setup
```

Runs all of the last four commands, i.e. `install`, `config`, `autoload`, and `dotenv`
