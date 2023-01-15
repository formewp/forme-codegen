### 🧪 test

You can use `test` to run one of the following test helper commands within your plugin or theme project directory.

```bash
forme test run
# or shorthand
forme test
```

This very simply runs `./tools/pest` under the hood. If you need to pass any arguments to pest, just use `./tools/pest` instead.

```bash
forme test setup
```

This will create a new `wp-test` directory if it doesn't already exist, containing a WordPress installation set up for integration and end to end tests, hooked up to an SQLite database, and with your plugin or theme installed via the magic of symlinks. The admin user is `admin` and the password is `password`.

```bash
forme test server start
forme test server stop
```

This will start or stop a test server using the basic built-in php server. It will be available to use on http://localhost:8000. While it's perfectly serviceable as a local development server, bear in mind that the database state won't persist between integration test runs, so don't rely on that aspect of it. If you need something more persistent, use valet or ketch (i.e. docker) instead.
