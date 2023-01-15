#!/usr/bin/env bash

# also make sure that wp cli is installed
WPCLI_EXISTS='command -v wp'
if ! $WPCLI_EXISTS &> /dev/null
then
echo "You need to install wp-cli globally to run the configuration script"
exit 1
fi

# and jq
JQ_EXISTS='command -v jq'
if ! $JQ_EXISTS &> /dev/null
then
echo "You need to install jq to run the configuration script"
exit 1
fi

# install forme base feeding via composer create-project with --no-script so it doesn't run all the scripts
composer create-project forme/base wp-test --no-scripts

# then remove stuff we don't need and run composer install-wordpress
cd wp-test
rm -rf vendor
rm composer.lock
jq 'del(.config."allow-plugins"."wikimedia/composer-merge-plugin")' composer.json > composer.tmp
mv composer.tmp composer.json
jq 'del(.extra."merge-plugin")' composer.json > composer.tmp
mv composer.tmp composer.json
composer remove wikimedia/composer-merge-plugin
composer install-wordpress
cd ..

pwd=`pwd`

# then run wp config create manually setting dbname, dbuser, dbpass and dbprefix
wp config create --dbname=$(pwd)"/wp-test/testing" --dbuser=dbuser --dbpass=password --dbprefix=wptests_ --skip-check

# wp config set constants WP_ENV, FORME_PRIVATE_ROOT, DB_DIR, DB_FILE, DISABLE_WP_CRON
wp config set WP_ENV testing
wp config set FORME_PRIVATE_ROOT $(pwd)"/"
wp config set DB_DIR $(pwd)"/wp-test/"
wp config set DB_FILE testing.sqlite3
wp config set DISABLE_WP_CRON true --raw

# requires & wrap forme_private_root in if statements
file="wp-test/public/wp-config.php"
# if  sed --version succceds then this is probably a linux system
if sed --version >/dev/null 2>&1; then
sed -i '/\/\* That'\''s all, stop editing\! Happy publishing\. \*\//a\
\
require_once FORME_PRIVATE_ROOT.'"'"'/vendor/autoload.php'"'"';\
require_once FORME_PRIVATE_ROOT.'"'"'/tests/support/helpers.php'"'"';\
require_once FORME_PRIVATE_ROOT.'"'"'/tests/support/setup.php'"'"';\
' $file

sed -i '/define( '\''FORME_PRIVATE_ROOT'\''/i\
\
if (!defined('"'"'FORME_PRIVATE_ROOT'"'"')) {\
' $file

sed -i '/define( '\''FORME_PRIVATE_ROOT'\''/a\
}\
' $file
# otherwise this is probably a mac, we need to add '' because of ancient sed
else
sed -i '' '/\/\* That'\''s all, stop editing\! Happy publishing\. \*\//a\
\
require_once FORME_PRIVATE_ROOT.'"'"'/vendor/autoload.php'"'"';\
require_once FORME_PRIVATE_ROOT.'"'"'/tests/support/helpers.php'"'"';\
require_once FORME_PRIVATE_ROOT.'"'"'/tests/support/setup.php'"'"';\
' $file

sed -i '' '/define( '\''FORME_PRIVATE_ROOT'\''/i\
\
if (!defined('"'"'FORME_PRIVATE_ROOT'"'"')) {\
' $file

sed -i '' '/define( '\''FORME_PRIVATE_ROOT'\''/a\
}\
' $file
fi
echo "Success: Require autoload and test bootstrap files"

# run composer init-dot-env
cd wp-test
composer init-dot-env
cd ..

# copy over server.php and db.php files
mv $TMP_DIR/server.php wp-test/public/server.php
mv $TMP_DIR/db.php wp-test/public/wp-content/db.php

# symlink the parent directory (like forme base link)
if [ $PROJECT_TYPE = 'plugin' ]; then
ln -s $(pwd) $(pwd)"/wp-test/public/wp-content/plugins/$PROJECT_NAME"
else
ln -s $(pwd) $(pwd)"/wp-test/public/wp-content/themes/$PROJECT_NAME"
fi

# wp core install
wp core install --url="http://localhost:8000" --title="Test Site" --admin_user="admin" --admin_password="password" --admin_email="test@example.com"

# activate required plugins
wp plugin activate advanced-custom-fields

if [ $PROJECT_TYPE = 'plugin' ]; then
wp plugin activate $PROJECT_NAME
else
wp theme activate $PROJECT_NAME
fi
