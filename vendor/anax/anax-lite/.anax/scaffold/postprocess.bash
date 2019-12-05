#!/usr/bin/env bash
#
# Postprocess scaffold
#

# Include ./functions.bash
source .anax/scaffold/functions.bash

# Install using composer
composer install

# Run own scaffolding scripts
for file in .anax/scaffold/postprocess.d/*.bash; do
    bash "$file"
done
