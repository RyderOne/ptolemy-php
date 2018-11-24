.PHONY: build

# ======================================================================================================
# If the first argument is one of the supported commands, pass the other terms as args for the first one
# https://stackoverflow.com/questions/2214575/passing-arguments-to-make-run
# ======================================================================================================
SUPPORTED_COMMANDS := command composer-update
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
  # use the rest as arguments for the command
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # Escape ":", "-" and "=" chars
  COMMAND_ARGS := $(subst :,\:,$(COMMAND_ARGS))
  # ...and turn them into do-nothing targets
  $(eval $(COMMAND_ARGS):;@:)
endif


###### COMPOSER #######

COMPOSER_OPTIONS= --rm -v $(shell pwd):/app --user $(UID):$(GID) --volume ~/.composer/cache:/tmp

composer-install:
	@docker run $(COMPOSER_OPTIONS) composer/composer install

composer-update:
	@docker run $(COMPOSER_OPTIONS) composer/composer update $(COMMAND_ARGS)

###### APP #######

PHP_IMAGE=php:7.1.5-alpine
VOLUME_TARGET=$(shell pwd)/code
VOLUME_OUTPUT=$(shell pwd)/output

DOCKER_RUN_OPTIONS= --rm -it \
					--user $(UID):$(GID) \
					--volume $(shell pwd):/usr/src/ptolemy-php \
					--volume $(VOLUME_TARGET):/usr/target \
					--volume $(VOLUME_OUTPUT):/usr/output \
					--workdir /usr/src/ptolemy-php


DOCKER_RUN=docker run $(DOCKER_RUN_OPTIONS) $(PHP_IMAGE) php

### PHAR ###
# Use this target to build the phar file
phar-build:
	@docker run --rm -v $(shell pwd):/usr/src --user $(UID):$(GID) ryderone/docker-box-project box build

# Use this target to test the phar file
phar-run:
	@$(DOCKER_RUN) ./build/ptolemy-php.phar map /usr/target /usr/output/

### DIRECT CONSOLE ###
ptolemy:
	@$(DOCKER_RUN) ./bin/ptolemy-php $(COMMAND_ARGS)

ptolemy-map:
	@$(DOCKER_RUN) ./bin/ptolemy-php map /usr/target/ /usr/output/
