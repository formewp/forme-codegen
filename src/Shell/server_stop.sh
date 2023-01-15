#!/usr/bin/env bash

# check if php.pid  exists
if ! [ -f "wp-test/php.pid" ]; then
  echo "It doesn't look like you have a server running. Try running forme test server start first."
  exit 1
fi

# todo: check also if the process is actually running
kill $(<wp-test/php.pid)
rm wp-test/php.pid
