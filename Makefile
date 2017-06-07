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
  COMMAND_ARGS := $(subst -,\-,$(COMMAND_ARGS))
  COMMAND_ARGS := $(subst =,\=,$(COMMAND_ARGS))
  # ...and turn them into do-nothing targets
  $(eval $(COMMAND_ARGS):;@:)
endif

# Use this target to test the phar file
run:
	@docker-compose run --rm ptolemy-php php ./build/ptolemy-php.phar

composer-install:
	@docker run --rm -v $(shell pwd):/app composer/composer install

composer-update:
	@docker run --rm -v $(shell pwd):/app composer/composer update $(COMMAND_ARGS)

# Use this target to build the phar file
build:
	@docker run --rm -v $(shell pwd):/usr/src ryderone/docker-box-project box build

command:
	@docker-compose run --rm ptolemy-php ./bin/ptolemy-php $(COMMAND_ARGS)