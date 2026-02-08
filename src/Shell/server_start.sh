#!/usr/bin/env bash

# check if php.pid already exists
if [ -f "wp-test/php.pid" ]; then
  echo "It looks like you already have a server running. Try running forme test server stop first if you need to restart it."
  exit 1
fi

# todo: make sure the process is not already running
cd wp-test/public
# 127.0.0.1 rather than localhost so that it doesn't use ipv6
nohup php -S 127.0.0.1:8000 server.php &> /dev/null &
echo $! > ../php.pid

cd ../..
