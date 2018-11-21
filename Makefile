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

COMPOSER_OPTIONS= --rm -v $(shell pwd):/app --user $(UID):$(GID) --volume ~/.composer/cache:/tmp

DOCKER_COMPOSE_RUN=docker-compose run --rm ptolemy-php

# Use this target to test the phar file
run:
	@$(DOCKER_COMPOSE_RUN) php ./build/ptolemy-php.phar map /usr/target

composer-install:
	@docker run $(COMPOSER_OPTIONS) composer/composer install

composer-update:
	@docker run $(COMPOSER_OPTIONS) composer/composer update $(COMMAND_ARGS)

# Use this target to build the phar file
build:
	@docker run --rm -v $(shell pwd):/usr/src --user $(UID):$(GID) ryderone/docker-box-project box build

command:
	@$(DOCKER_COMPOSE_RUN) ./bin/ptolemy-php $(COMMAND_ARGS)

map:
	@$(DOCKER_COMPOSE_RUN) ./bin/ptolemy-php map -v /usr/target/