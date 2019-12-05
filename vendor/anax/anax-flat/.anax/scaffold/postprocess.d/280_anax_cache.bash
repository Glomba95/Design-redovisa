#!/usr/bin/env bash
#
# anax/cache
#
# Setup cache/ and related.
#

# Create the cache directory.
install -d cache

# Get configuration for the cache.
rsync -a vendor/anax/cache/config ./
