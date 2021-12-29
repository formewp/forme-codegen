#!/usr/bin/env bash

COMPOSE="docker-compose"

# If we pass any arguments...
if [ $# -gt 0 ];then

    # wp
    if [ "$1" == "wp" ]; then
        shift 1
        $COMPOSE run --rm \
            -w /var/www/html \
            app \
            wp "$@"

    # composer
    elif [ "$1" == "composer" ]; then
        shift 1
        $COMPOSE run --rm \
            -w /var/www/html \
            app \
            composer "$@"

    # npm
    elif [ "$1" == "npm" ]; then
        shift 1
        $COMPOSE run --rm \
            -w /var/www/html \
            app \
            npm "$@"

    # npx
    elif [ "$1" == "npm" ]; then
        shift 1
        $COMPOSE run --rm \
            -w /var/www/html \
            app \
            npx "$@"

    # compose up with -d
    elif [ "$1" == "up" ]; then
        shift 1
        $COMPOSE up -d "$@"

    # Else, pass-thru args to docker-compose
    else
        $COMPOSE "$@"
    fi

else
    $COMPOSE ps
fi
