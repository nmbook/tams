#!/bin/bash

# full update for betaweb
git pull
chmod go+rx data design seeder.py betaweb-pull.sh
chmod go+r * data/* design/*

# old filename
#ln -fs ta-list.php talist-tuesdaynight.php

# view source of all .php files
#for FILE in *.php
#do
#    ln -fs $FILE `echo $FILE | sed 's/\(.*\.\)php/\1txt/'`
#done

