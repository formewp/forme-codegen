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

    # exec
    elif [ "$1" == "shell" ]; then
        shift 1
        # check if the container is running first
        if [ "$(docker ps -q -f name=$CONTAINER)" ]; then
            docker exec -it $CONTAINER bash "$@"
        else
            echo "The container does not appear to be running. Please run 'forme ketch up' first."
            exit 1
        fi

    # list alias for ps
    elif [ "$1" == "list" ]; then
        shift 1
        $COMPOSE ps "$@"

    # Else, pass-thru args to docker-compose
    else
        $COMPOSE "$@"
    fi

else
    $COMPOSE ps
fi
