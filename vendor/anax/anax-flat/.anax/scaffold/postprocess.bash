#!/usr/bin/env bash
#
# Postprocess scaffold
#

# Include ./functions.bash
source .anax/scaffold/functions.bash

# Install using composer
composer install

# Get scaffolding scripts from Anax Lite
rsync -a vendor/anax/anax-lite/.anax/scaffold/ .anax/scaffold/anax-lite/

# Run scaffolding scripts from Anax Lite
for file in .anax/scaffold/anax-lite/postprocess.d/*.bash; do
    bash "$file"
done

# Run own scaffolding scripts
for file in .anax/scaffold/postprocess.d/*.bash; do
    bash "$file"
done


# # Copy default config for controller
# rsync -a vendor/anax/controller/config/ config/

# Copy default config for page
rsync -a vendor/anax/page/config/ config/

# Copy default config for textfilter
rsync -a vendor/anax/textfilter/config/ config/

# Copy default config for view
install -d view
rsync -a vendor/anax/view/config/ config/

# Create directory structure for htdocs
install -d htdocs/img
rsync -a vendor/anax/commons/htdocs/ htdocs/

# Install mosbth/cimage into htdocs
make cimage-install


#
# Review these
#

# # Add default files to make it look oophp-me
# make cimage-config-create
# rsync -a .scaffold/default/ ./
