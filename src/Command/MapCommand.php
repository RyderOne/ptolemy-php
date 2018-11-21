<?php

namespace PtolemyPHP\Command;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PtolemyPHP\Visitor\NodeVisitor;
use PtolemyPHP\Store\CallStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class MapCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('map')
            ->setDescription('Parse the directory and map class / methods')
            ->addArgument('directory', InputArgument::REQUIRED, 'The directory to load')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser     = new NodeTraverser;
        $prettyPrinter = new PrettyPrinter\Standard;


        $traverser->addVisitor(new NameResolver);
        $traverser->addVisitor(new NodeVisitor);


        $finder = new Finder();
        $finder->files()->in($directory);

        foreach ($finder as $file) {
            try {
                dump('FILE : '.$file->getRelativePathname());
                $code = $file->getContents();

                // parse
                $stmts = $parser->parse($code);

                // traverse
                $stmts = $traverser->traverse($stmts);
            } catch (PhpParser\Error $e) {
                echo 'Parse Error: ', $e->getMessage();
            }
        }

        CallStore::resolveRawRelations();
        CallStore::dumpRelations();
    }
}
