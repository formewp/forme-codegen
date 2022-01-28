You can use ketch to configure a new docker container, as well as run simple `docker-compose` commands like `up`, `down`, `restart`, `list` etc.

You can also run a selection of commands within the configured container, such as `composer`, `npm`, `npx` and `wp`, or you can use `shell` to open a bash prompt in the container and run arbitrary commands.

You'll need `docker` & `docker-compose` installed as well as `wp cli`.

```bash
forme ketch init
```

You'll need to run this from within an existing base installation. This will create all the relevant docker boilerplate for your project, and should also update your `wp-config.php` with the relevant db credentials and other settings.


```bash
# 🍅🍅🍅
forme ketch up
```

This will spin up your app and mysql containers. You should be able to access your project from the browser at http://localhost and install WordPress.

```bash
forme ketch down
```

This will stop and remove the running containers.

```bash
forme ketch restart
```

This stops and starts the running containers.

``bash
forme ketch link /path/to/your/plugin/or/theme/repo
```

Links plugin or theme repo directories into your container.

```bash
forme ketch list
```

Lists running containers (equivalent to `docker-compose ps`)

```bash
forme ketch composer require foo/bar
forme ketch wp forme-queue
forme ketch npm install
forme ketch npx lerna bootstrap
```

You can run `composer`, `wp`, `npm` and `npx` commands within the container. NB: flags don't work.

```bash
forme ketch shell
```

Full shell access within the container in case you need to run more complex commands.
