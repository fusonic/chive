#!/bin/sh

VERSION=chive_$1

rm -rf /tmp/$VERSION/
mkdir /tmp/$VERSION/
mkdir /tmp/$VERSION/chive/
echo exporting to /tmp/$VERSION/chive/
git checkout-index -f -a --prefix=/tmp/$VERSION/chive/
chmod 0777 -R /tmp/$VERSION/chive/assets
chmod 0777 -R /tmp/$VERSION/chive/protected/runtime/

cd /tmp/$VERSION/
touch chive/index_changed.php
cat chive/index.php | sed "s/'YII_DEBUG', true/'YII_DEBUG', false/" >> chive/index_changed.php
rm chive/.gitignore
rm chive/index.php
rm chive/archiveExport.sh
rm chive/protected/tests -rf
mv chive/pharExport.php pharExport.php
mv chive/index_changed.php chive/index.php

tar czf chive_$1.tar.gz chive
zip -rq chive_$1.zip chive

# Download JSMin
echo 'Download & compile JSMin ...'
wget https://raw.github.com/douglascrockford/JSMin/master/jsmin.c
gcc -o jsmin jsmin.c

# Do phar export
echo 'Run phar export ...'
/usr/bin/php pharExport.php $1
rm pharExport.php

# Delete JSMin
rm jsmin jsmin.c
