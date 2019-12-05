#!/usr/bin/env make
#
# An Anax module.
# See organisation on GitHub: https://github.com/canax

# ------------------------------------------------------------------------
#
# General stuff, reusable for all Makefiles.
#

# Detect OS
OS = $(shell uname -s)

# Defaults
ECHO = echo

# Make adjustments based on OS
ifneq (, $(findstring CYGWIN, $(OS)))
	ECHO = /bin/echo -e
endif

# Colors and helptext
NO_COLOR	= \033[0m
ACTION		= \033[32;01m
OK_COLOR	= \033[32;01m
ERROR_COLOR	= \033[31;01m
WARN_COLOR	= \033[33;01m

# Print out colored action message
ACTION_MESSAGE = $(ECHO) "$(ACTION)---> $(1)$(NO_COLOR)"

# Which makefile am I in?
WHERE-AM-I = $(CURDIR)/$(word $(words $(MAKEFILE_LIST)),$(MAKEFILE_LIST))
THIS_MAKEFILE := $(call WHERE-AM-I)

# Echo some nice helptext based on the target comment
HELPTEXT = $(call ACTION_MESSAGE, $(shell egrep "^\# target: $(1) " $(THIS_MAKEFILE) | sed "s/\# target: $(1)[ ]*-[ ]* / /g"))

# Check version  and path to command and display on one line
CHECK_VERSION = printf "%-15s %-10s %s\n" "`basename $(1)`" "`$(1) --version $(2)`" "`which $(1)`"

# Get current working directory, it may not exist as environment variable.
PWD = $(shell pwd)

# target: help                    - Displays help.
.PHONY:  help
help:
	@$(call HELPTEXT,$@)
	@sed '/^$$/q' $(THIS_MAKEFILE) | tail +3 | sed 's/#\s*//g'
	@$(ECHO) "Usage:"
	@$(ECHO) " make [target] ..."
	@$(ECHO) "target:"
	@egrep "^# target:" $(THIS_MAKEFILE) | sed 's/# target: / /g'



# ------------------------------------------------------------------------
#
# Specifics for this project.
#
# Default values for arguments
container ?= latest

BIN     := .bin
PHPUNIT := $(BIN)/phpunit
PHPLOC 	:= $(BIN)/phploc
PHPCS   := $(BIN)/phpcs
PHPCBF  := $(BIN)/phpcbf
PHPMD   := $(BIN)/phpmd
PHPDOC  := $(BIN)/phpdoc
BEHAT   := $(BIN)/behat
SHELLCHECK := $(BIN)/shellcheck
BATS := $(BIN)/bats



# --------------------------------------------------------------------------
#
# Specifics for website projects
#
WWW_SITE		:= rem.dbwebb.se
WWW_LOCAL		:= local.$(WWW_SITE)
SERVER_ADMIN 	:= mos@$(WWW_SITE)
BASE_URL    	:= https://$(WWW_SITE)/

GIT_BASE 		:= git/$(WWW_SITE)
HTDOCS_BASE 	:= $(HOME)/htdocs
LOCAL_HTDOCS 	:= $(HTDOCS_BASE)/$(WWW_SITE)
ROBOTSTXT	 	:= robots.txt

# Certificates for https
SSL_PEM_BASE 	= /etc/letsencrypt/live/$(WWW_SITE)

# Publish
EXCLUDE_ON_PUBLISH = --exclude old --exclude backup --exclude .git --exclude .solution --exclude .solutions --exclude error.log --exclude cache --exclude access.log --delete



# target: prepare                 - Prepare for tests and build
.PHONY:  prepare
prepare:
	@$(call HELPTEXT,$@)
	[ -d .bin ] || mkdir .bin
	[ -d build ] || mkdir build
	rm -rf build/*



# target: clean                   - Removes generated files and directories.
.PHONY: clean
clean:
	@$(call HELPTEXT,$@)
	rm -rf build



# target: clean-cache             - Clean the cache.
.PHONY:  clean-cache
clean-cache:
	@$(call HELPTEXT,$@)
	rm -rf cache/*/*



# target: clean-all               - Removes generated files and directories.
.PHONY:  clean-all
clean-all: clean clean-cache
	@$(call HELPTEXT,$@)
	rm -rf .bin vendor



# target: check                   - Check version of installed tools.
.PHONY:  check
check: check-tools-bash check-tools-php check-docker
	@$(call HELPTEXT,$@)



# target: test                    - Run all tests.
.PHONY:  test
test: phpunit phpcs phpmd phploc behat shellcheck bats
	@$(call HELPTEXT,$@)
	composer validate



# target: doc                     - Generate documentation.
.PHONY:  doc
doc: phpdoc
	@$(call HELPTEXT,$@)



# target: build                   - Do all build
.PHONY:  build
build: test doc #theme less-compile less-minify js-minify
	@$(call HELPTEXT,$@)



# target: install                 - Install all tools
.PHONY:  install
install: prepare install-production install-tools-php install-tools-bash
	@$(call HELPTEXT,$@)
	[ ! -f composer.json ] || composer install



# target: install-production      - Install tools needed for production
.PHONY:  install-production
install-production: 
	@$(call HELPTEXT,$@)
	[ ! -f composer.json ] || composer --no-dev install



# target: update                  - Update the codebase and tools.
.PHONY:  update
update:
	@$(call HELPTEXT,$@)
	[ ! -d .git ] || git pull
	composer install



# target: tag-prepare             - Prepare to tag new version.
.PHONY: tag-prepare
tag-prepare:
	@$(call HELPTEXT,$@)



# ----------------------------------------------------------------------------
#
# docker
#
# target: docker-up               - Start all docker container="", or specific, default "latest".
.PHONY: docker-up
docker-up:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml up -d $(container)



# target: docker-stop             - Stop running docker containers.
.PHONY: docker-stop
docker-stop:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml stop



# target: docker-run              - Run container="" with what="" one off command.
.PHONY: docker-run
docker-run:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml run $(container) $(what)



# target: docker-bash             - Run container="" with what="bash" one off command.
.PHONY: docker-bash
docker-bash:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml run $(container) bash



# target: docker-exec             - Run container="" with what="" command in running container.
.PHONY: docker-exec
docker-exec:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml exec $(container) $(what)



# target: docker-install          - Run make install in container="".
.PHONY: docker-install
docker-install:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml run $(container) make install



# target: docker-test             - Run make test in container="".
.PHONY: docker-test
docker-test:
	@$(call HELPTEXT,$@)
	[ ! -f docker-compose.yaml ] || docker-compose -f docker-compose.yaml run $(container) make test



# target: check-docker            - Check versions of docker.
.PHONY: check-docker
check-docker:
	@$(call HELPTEXT,$@)
	@$(call CHECK_VERSION, docker, | cut -d" " -f3-)
	@$(call CHECK_VERSION, docker-compose, | cut -d" " -f3-)



# ------------------------------------------------------------------------
#
# PHP
#

# target: install-tools-php       - Install PHP development tools.
.PHONY: install-tools-php
install-tools-php:
	@$(call HELPTEXT,$@)
	#curl -Lso $(PHPDOC) https://www.phpdoc.org/phpDocumentor.phar && chmod 755 $(PHPDOC)
	curl -Lso $(PHPDOC) https://github.com/phpDocumentor/phpDocumentor2/releases/download/v2.9.0/phpDocumentor.phar && chmod 755 $(PHPDOC)

	curl -Lso $(PHPCS) https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar && chmod 755 $(PHPCS)

	curl -Lso $(PHPCBF) https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar && chmod 755 $(PHPCBF)

	curl -Lso $(PHPMD) http://static.phpmd.org/php/latest/phpmd.phar && chmod 755 $(PHPMD)

	curl -Lso $(PHPLOC) https://phar.phpunit.de/phploc.phar && chmod 755 $(PHPLOC)

	curl -Lso $(BEHAT) https://github.com/Behat/Behat/releases/download/v3.3.0/behat.phar && chmod 755 $(BEHAT)

	# Get PHPUNIT depending on current PHP installation
	curl -Lso $(PHPUNIT) https://phar.phpunit.de/phpunit-$(shell \
	 	php -r "echo version_compare(PHP_VERSION, '7.0', '<') \
			? '5' \
			: (version_compare(PHP_VERSION, '7.1', '>=') \
				? '7' \
				: '6'\
		);" \
		).phar && chmod 755 $(PHPUNIT)



# target: check-tools-php         - Check versions of PHP tools.
.PHONY: check-tools-php
check-tools-php:
	@$(call HELPTEXT,$@)
	php --version && echo
	composer show && echo
	@$(call CHECK_VERSION, $(PHPUNIT))
	@$(call CHECK_VERSION, $(PHPLOC))
	@$(call CHECK_VERSION, $(PHPCS))
	@$(call CHECK_VERSION, $(PHPMD))
	@$(call CHECK_VERSION, $(PHPCBF))
	@$(call CHECK_VERSION, $(PHPDOC))
	@$(call CHECK_VERSION, $(BEHAT))



# target: phpunit                 - Run unit tests for PHP.
.PHONY: phpunit
phpunit: prepare
	@$(call HELPTEXT,$@)
	[ ! -d "test" ] || $(PHPUNIT) --configuration .phpunit.xml



# target: phpcs                   - Codestyle for PHP.
.PHONY: phpcs
phpcs: prepare
	@$(call HELPTEXT,$@)
	[ ! -f .phpcs.xml ] || $(PHPCS) --standard=.phpcs.xml | tee build/phpcs



# target: phpcbf                  - Fix codestyle for PHP.
.PHONY: phpcbf
phpcbf:
	@$(call HELPTEXT,$@)
ifneq ($(wildcard test),)
	[ ! -f .phpcs.xml ] || $(PHPCBF) --standard=.phpcs.xml
else
	[ ! -f .phpcs.xml ] || $(PHPCBF) --standard=.phpcs.xml src
endif



# target: phpmd                   - Mess detector for PHP.
.PHONY: phpmd
phpmd: prepare
	@$(call HELPTEXT,$@)
	- [ ! -f .phpmd.xml ] || $(PHPMD) . text .phpmd.xml | tee build/phpmd



# target: phploc                  - Code statistics for PHP.
.PHONY: phploc
phploc: prepare
	@$(call HELPTEXT,$@)
	$(PHPLOC) src > build/phploc



# target: phpdoc                  - Create documentation for PHP.
.PHONY: phpdoc
phpdoc:
	@$(call HELPTEXT,$@)
	[ ! -d doc ] || $(PHPDOC) --config=.phpdoc.xml



# target: behat                   - Run behat for feature tests.
.PHONY: behat
behat:
	@$(call HELPTEXT,$@)
	[ ! -d features ] || $(BEHAT)


# ------------------------------------------------------------------------
#
# Bash
#

# target: install-tools-bash      - Install Bash development tools.
.PHONY: install-tools-bash
install-tools-bash:
	@$(call HELPTEXT,$@)
	# Shellcheck
	curl -s https://storage.googleapis.com/shellcheck/shellcheck-latest.linux.x86_64.tar.xz | tar -xJ -C build/ && rm -f .bin/shellcheck && ln build/shellcheck-latest/shellcheck .bin/

	# Bats
	curl -Lso $(BIN)/bats-exec-suite https://raw.githubusercontent.com/sstephenson/bats/master/libexec/bats-exec-suite
	curl -Lso $(BIN)/bats-exec-test https://raw.githubusercontent.com/sstephenson/bats/master/libexec/bats-exec-test
	curl -Lso $(BIN)/bats-format-tap-stream https://raw.githubusercontent.com/sstephenson/bats/master/libexec/bats-format-tap-stream
	curl -Lso $(BIN)/bats-preprocess https://raw.githubusercontent.com/sstephenson/bats/master/libexec/bats-preprocess
	curl -Lso $(BATS) https://raw.githubusercontent.com/sstephenson/bats/master/libexec/bats
	chmod 755 $(BIN)/bats*



# target: check-tools-bash        - Check versions of Bash tools.
.PHONY: check-tools-bash
check-tools-bash:
	@$(call HELPTEXT,$@)
	which $(SHELLCHECK) && $(SHELLCHECK) --version
	which $(BATS) && $(BATS) --version



# target: shellcheck              - Run shellcheck for bash files.
.PHONY: shellcheck
shellcheck:
	@$(call HELPTEXT,$@)
	[ ! -f src/*.bash ] || $(SHELLCHECK) --shell=bash src/*.bash



# target: bats                    - Run bats for unit testing bash files.
.PHONY: bats
bats:
	@$(call HELPTEXT,$@)
	[ ! -d bats ] || $(BATS) bats/



# ------------------------------------------------------------------------
#
# Theme
#
# target: theme                   - Do make build install in theme/ if available.
.PHONY: theme
theme:
	@$(call HELPTEXT,$@)
	[ ! -d theme ] || $(MAKE) --directory=theme build install
	#[ ! -d theme ] || ( cd theme && make build install )



# ------------------------------------------------------------------------
#
# Cimage
#

define CIMAGE_CONFIG
<?php
return [
    "mode"         => "development",
    "image_path"   =>  __DIR__ . "/../img/",
    "cache_path"   =>  __DIR__ . "/../../cache/cimage/",
    "autoloader"   =>  __DIR__ . "/../../vendor/autoload.php",
];
endef
export CIMAGE_CONFIG

define GIT_IGNORE_FILES
# Ignore everything in this directory
*
# Except this file
!.gitignore
endef
export GIT_IGNORE_FILES

# target: cimage-update           - Install/update Cimage to latest version.
.PHONY: cimage-update
cimage-update:
	@$(call HELPTEXT,$@)
	composer require mos/cimage
	install -d htdocs/img htdocs/cimage cache/cimage
	chmod 777 cache/cimage
	$(ECHO) "$$GIT_IGNORE_FILES" | bash -c 'cat > cache/cimage/.gitignore'
	cp vendor/mos/cimage/webroot/img.php htdocs/cimage
	cp vendor/mos/cimage/webroot/img/car.png htdocs/img/
	touch htdocs/cimage/img_config.php

# target: cimage-config-create    - Create configfile for Cimage.
.PHONY: cimage-config-create
cimage-config-create:
	@$(call HELPTEXT,$@)
	$(ECHO) "$$CIMAGE_CONFIG" | bash -c 'cat > htdocs/cimage/img_config.php'
	cat htdocs/cimage/img_config.php



# ------------------------------------------------------------------------
#
# Apache development and production server setup
#

# target: install-fresh           - Do a fresh installation of a new development and/or production server.
.PHONY: install-fresh
install-fresh: etc-hosts site-build local-publish virtual-host
	@$(call HELPTEXT,$@)



# target: etc-hosts               - Create a entry in the /etc/hosts for local access.
.PHONY: etc-hosts
etc-hosts:
	@$(call HELPTEXT,$@)
	$(ECHO) "127.0.0.1 $(WWW_LOCAL)" | sudo bash -c 'cat >> /etc/hosts'
	@tail -1 /etc/hosts



# target: site-build              - Create essential directories to run as local website.
.PHONY: site-build
site-build:
	@$(call HELPTEXT,$@)
	# Create the local htdocs directory
	install -d $(LOCAL_HTDOCS)/log

# Create and sync cache
#bash -c "install -d -m 777 cache/{cimage,anax,forum,forum-files}"
#rsync -av cache $(LOCAL_HTDOCS)

# Copy from CImage
#install -d htdocs/cimage
#bash -c "rsync -av #vendor/mos/cimage/webroot/{img,imgd,imgf,imgp,imgs,check_system}.php #vendor/mos/cimage/icc htdocs/cimage"
#rsync -av vendor/mos/cimage/webroot/img/ htdocs/img/cimage/



# target: local-publish           - Publish website to local host.
.PHONY: local-publish
local-publish:
	@$(call HELPTEXT,$@)
	rsync -av $(EXCLUDE_ON_PUBLISH) config content htdocs vendor $(LOCAL_HTDOCS)

	@# Enable robots if available
	[ ! -f $(ROBOTSTXT) ] || cp $(ROBOTSTXT) "$(LOCAL_HTDOCS)/htdocs/robots.txt"



# target: clean-cache-anax        - Clean the local anax cache directory.
.PHONY: clean-cache-anax
clean-cache-anax:
	@$(call HELPTEXT,$@)
	-rm -f cache/anax/*



# target: local-cache-clear       - Clear the local htdocs cache.
.PHONY: local-cache-clear
local-cache-clear:
	@$(call HELPTEXT,$@)
	-sudo rm -f $(LOCAL_HTDOCS)/cache/anax/*



# target: local-publish-clear     - Publish website to local host and clear the cache.
.PHONY: local-publish-clear
local-publish-clear: local-cache-clear local-publish
	@$(call HELPTEXT,$@)



# target: production-publish      - Publish latest to the production server.
.PHONY: production-publish
production-publish:
	@$(call HELPTEXT,$@)
	ssh $(SERVER_ADMIN) -t "cd $(GIT_BASE) && git pull && make update local-publish"


# target: ssl-cert-create         - Create the HTTPS certificates.
.PHONY: ssl-cert-create
ssl-cert-create:
	@$(call HELPTEXT,$@)
	sudo service apache2 stop
	sudo certbot certonly --standalone -d $(WWW_SITE) -d www.$(WWW_SITE)
	sudo service apache2 start



# target: ssl-cert-update         - Update certificates with new expiray date.
.PHONY: ssl-cert-renew
ssl-cert-renew:
	@$(call HELPTEXT,$@)
	sudo service apache2 stop
	sudo certbot renew
	sudo service apache2 start



# target: virtual-host            - Create files for the virtual host http configuration.
.PHONY: virtual-host

define VIRTUAL_HOST_80
Define site $(WWW_SITE)
ServerAdmin $(SERVER_ADMIN)

<VirtualHost *:80>
	ServerName $${site}
	ServerAlias local.$${site}
	ServerAlias do3.$${site}
	DocumentRoot $(HTDOCS_BASE)/$${site}/htdocs
	ServerSignature Off

	Include $(HTDOCS_BASE)/$${site}/config/apache/env.conf
	Include $(HTDOCS_BASE)/$${site}/config/apache/redirect.conf
	Include $(HTDOCS_BASE)/$${site}/config/apache/rewrite.conf

	<Directory />
		#FallbackResource /index.php (did not work as expected)

		# Rewrite to frontcontroller
		RewriteEngine on
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule .* index.php [NC,L]

		Options Indexes FollowSymLinks
		AllowOverride None
		Require all granted
		Order allow,deny
		Allow from all
		Deny from env=spambot
	</Directory>

	<FilesMatch "\.(jpe?g|png|gif|js|css|svg|ttf|otf|eot|woff|woff2|ico)$">
		ExpiresActive On
		ExpiresDefault "access plus 1 week"
	</FilesMatch>

	#LogLevel alert rewrite:trace6
	# tail -f error.log | fgrep '[rewrite:'

	ErrorLog  $(HTDOCS_BASE)/$${site}/log/error.log
	CustomLog $(HTDOCS_BASE)/$${site}/log/access.log combined
</VirtualHost>
endef
export VIRTUAL_HOST_80

define VIRTUAL_HOST_80_WWW
Define site $(WWW_SITE)
ServerAdmin $(SERVER_ADMIN)

<VirtualHost *:80>
	ServerName www.$${site}
	Redirect "/" "http://$${site}/"
</VirtualHost>
endef
export VIRTUAL_HOST_80_WWW

virtual-host:
	@$(call HELPTEXT,$@)
	install -d $(LOCAL_HTDOCS)/htdocs
	$(ECHO) "$$VIRTUAL_HOST_80" | sudo bash -c 'cat > /etc/apache2/sites-available/$(WWW_SITE).conf'
	$(ECHO) "$$VIRTUAL_HOST_80_WWW" | sudo bash -c 'cat > /etc/apache2/sites-available/www.$(WWW_SITE).conf'
	sudo a2ensite $(WWW_SITE) www.$(WWW_SITE)
	sudo a2enmod rewrite expires setenvif # cgi
	sudo apachectl configtest
	sudo service apache2 reload



# target: virtual-host-echo       - Echo virtual host configuration for http.
virtual-host-echo:
	@$(call HELPTEXT,$@)
	@$(ECHO) "$$VIRTUAL_HOST_80"
	#$(ECHO) "$$VIRTUAL_HOST_80_WWW"



# target: virtual-host-https      - Upgrade from http to https apache virtual host config files.
.PHONY: virtual-host-https

define VIRTUAL_HOST_443
Define site $(WWW_SITE)
ServerAdmin $(SERVER_ADMIN)

<VirtualHost *:80>
	ServerName $${site}
	ServerAlias do3.$${site}
	Redirect "/" "https://$${site}/"
</VirtualHost>

<VirtualHost *:443>
	Include $(HTDOCS_BASE)/$${site}/config/apache/ssl.conf
	SSLCertificateFile      $(SSL_PEM_BASE)/cert.pem
	SSLCertificateKeyFile   $(SSL_PEM_BASE)/privkey.pem
	SSLCertificateChainFile $(SSL_PEM_BASE)/chain.pem

	ServerName $${site}
	ServerAlias do1.$${site}
	ServerAlias do2.$${site}
	DocumentRoot $(HTDOCS_BASE)/$${site}/htdocs
	ServerSignature Off

	Include $(HTDOCS_BASE)/$${site}/config/apache/env.conf
	Include $(HTDOCS_BASE)/$${site}/config/apache/redirect.conf
	Include $(HTDOCS_BASE)/$${site}/config/apache/rewrite.conf

	<Directory />
		#FallbackResource /index.php (did not work as expected)

		# Rewrite to frontcontroller
		RewriteEngine on
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule .* index.php [NC,L]

		Options Indexes FollowSymLinks
		AllowOverride None
		Require all granted
		Order allow,deny
		Allow from all
		Deny from env=spambot

		Options +ExecCGI
		AddHandler cgi-script .cgi

	</Directory>

	<FilesMatch "\.(jpe?g|png|gif|js|css|svg|ttf|otf|eot|woff|woff2|ico)$">
		ExpiresActive On
		ExpiresDefault "access plus 1 week"
	</FilesMatch>

	#LogLevel alert rewrite:trace6
	# tail -f error.log | fgrep '[rewrite:'

	ErrorLog  $(HTDOCS_BASE)/$${site}/log/error.log
	CustomLog $(HTDOCS_BASE)/$${site}/log/access.log combined
</VirtualHost>
endef
export VIRTUAL_HOST_443

define VIRTUAL_HOST_443_WWW
Define site $(WWW_SITE)
ServerAdmin $(SERVER_ADMIN)

<VirtualHost *:80>
	ServerName www.$${site}
	Redirect "/" "https://www.$${site}/"
</VirtualHost>

<VirtualHost *:443>
	Include $(HTDOCS_BASE)/$${site}/config/apache/ssl.conf
	SSLCertificateFile      $(SSL_PEM_BASE)/cert.pem
	SSLCertificateKeyFile   $(SSL_PEM_BASE)/privkey.pem
	SSLCertificateChainFile $(SSL_PEM_BASE)/chain.pem

	ServerName www.$${site}
	Redirect "/" "https://$${site}/"
</VirtualHost>
endef
export VIRTUAL_HOST_443_WWW

virtual-host-https:
	@$(call HELPTEXT,$@)
	$(ECHO) "$$VIRTUAL_HOST_443" | sudo bash -c 'cat > /etc/apache2/sites-available/$(WWW_SITE).conf'
	$(ECHO) "$$VIRTUAL_HOST_443_WWW" | sudo bash -c 'cat > /etc/apache2/sites-available/www.$(WWW_SITE).conf'
	sudo a2enmod ssl
	sudo apachectl configtest
	sudo service apache2 reload
