<?php

namespace PtolemyPHP\Command;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PtolemyPHP\Store\CallStore;
use PtolemyPHP\Visitor\NodeVisitor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class MapCommand extends Command
{
    const DEFAULT_FILENAME = 'output.json';

    protected function configure()
    {
        $this
            ->setName('map')
            ->setDescription('Parse the directory and map class / methods')
            ->addArgument('directory', InputArgument::REQUIRED, 'The directory to load')
            ->addArgument('output', InputArgument::REQUIRED, 'The directory where to output the result')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser     = new NodeTraverser;

        $traverser->addVisitor(new NameResolver);
        $traverser->addVisitor(new NodeVisitor);

        $finder = new Finder();
        $finder->files()->in($directory);

        foreach ($finder as $file) {
            try {
                $code = $file->getContents();
                $stmts = $parser->parse($code);
                $traverser->traverse($stmts);
            } catch (Error $e) {
                echo 'Parse Error: ', $e->getMessage();
            }
        }

        CallStore::resolveRawRelations();
        $json = CallStore::toJsonArray();

        file_put_contents($input->getArgument('output').'/'.self::DEFAULT_FILENAME, json_encode($json));
    }
}
