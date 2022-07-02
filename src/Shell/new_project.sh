#!/bin/bash
if ! command -v git &> /dev/null
then
    echo "You need to install git before running this"
    exit 1
fi
if ! command -v npm &> /dev/null
then
    echo "You need to install node and npm before running this"
    exit 1
fi
if ! command -v composer &> /dev/null
then
    echo "You need to install composer before running this"
    exit 1
fi
git clone git@github.com:formewp/forme-project-type.git project-name-project-type
cd project-name-project-type
rm -rf .git
npm i
npx gulp --name="ProjectName" --vendor="VendorName" --view="ViewEngine"
rm gulpfile.js
rm -rf node_modules
rm package.json
rm package-lock.json
mv package.json.stub package.json
npm i
if command -v git-cliff &> /dev/null
then
    git cliff --init
else
    echo "We couldn't initialise git cliff. You should install it with 'brew install git-cliff' or 'cargo install git-cliff'"
    echo "Then run 'git cliff --init' in the project directory"
fi
composer install
git init
git add --all
git commit --all -m "Initial Commit"
composer hooks
cd ..
