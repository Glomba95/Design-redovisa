#!/usr/bin/env bash
#
# Postprocess scaffold
#

# Include ./functions.bash
source .anax/scaffold/functions.bash

# Install using composer
composer install

echo "SCAFFOLDING NOT YET IMPLEMENTED".

# # Get scaffolding scripts from Anax Lite
# rsync -a vendor/anax/anax-lite/.anax/scaffold/ .anax/scaffold/anax-lite/
# 
# # Run scaffolding scripts from Anax Lite
# for file in .anax/scaffold/anax-lite/postprocess.d/*.bash; do
#     bash "$file"
# done
# 
# # Run own scaffolding scripts
# for file in .anax/scaffold/postprocess.d/*.bash; do
#     bash "$file"
# done
