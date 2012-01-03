#!/bin/sh

VERSION=chive_$1

sudo rm -rf /tmp/$VERSION/
sudo mkdir /tmp/$VERSION/
sudo mkdir /tmp/$VERSION/chive/
echo exporting to /tmp/$VERSION/chive/
sudo bzr export /tmp/$VERSION/chive/
sudo chmod 0777 -R /tmp/$VERSION/chive/assets
sudo chmod 0777 -R /tmp/$VERSION/chive/protected/runtime/

cd /tmp/$VERSION/
sudo touch chive/index_changed.php
sudo cat chive/index.php | sed "s/'YII_DEBUG', true/'YII_DEBUG', false/" >> chive/index_changed.php
sudo rm chive/index.php
sudo mv chive/index_changed.php chive/index.php

sudo tar czf chive_$1.tar.gz chive
sudo zip -rq chive_$1.zip chive