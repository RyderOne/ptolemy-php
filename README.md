# Ptolemy-PHP
PHP source code static map generator

## Purpose

The main goal of this project is to provide an easy way to visually inspect how php classes and methods interact with each other.
When you start on an existing php project (e.g. symfony app), at the beginning it may be hard to understand which method from a class calls which other method from other class. You have to inspect the code, see each file, build a mental model of how the app works.

Even on your own project, after 3 months, 10 months, 1 year, it's not always easy to remember everything about each component.

Ptolemy-PHP traverses statically your src directory (no need to run your app) and build this mental model for you. It's here to *help* you. It creates a json file describing relations between your classes, your methods.

Then you can use this json file as an input for other tools to generate an appropriate graph and *see* relations on a graph

## How to use

### Direct php usage

    php bin/ptolemy-php map /path/to/src /path/to/output/directory

### Development / Docker

You can use Makefile provided to build phar file (and use the app everywhere) or simply run the command without installing php by using a docker container.

By default, the directory `code` is used as an example src. You can build the `.phar` file with the command `make phar-build` then mapping your own src directory, or copy your directory into the `test` folder and change the variable `VOLUME_TARGET` in the Makefile.

## How it works

Ptolemy-PHP uses the excellent project [PHP-Parser](https://github.com/nikic/PHP-Parser) from [Nikita Popov](https://github.com/nikic) (big thanks to him)
Internally it uses PHP-Parser's node traverser and collect information about classes, arguments and methods to build an index of relations, then dump it into a json file.

## TODO

- Upload the project to packagist