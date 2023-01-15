#!/usr/bin/env bash

# check if php.pid already exists
if [ -f "wp-test/php.pid" ]; then
  echo "It looks like you already have a server running. Try running forme test server stop first if you need to restart it."
  exit 0
fi

# todo: make sure the process is not already running
cd wp-test/public
nohup php -S localhost:8000 server.php &> /dev/null &
echo $! > ../php.pid

cd ../..
