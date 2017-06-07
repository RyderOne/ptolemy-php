<?php

namespace PtolemyPHP\Visitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\PropertyProperty;
use PtolemyPHP\Store\CallStore;

class NodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        $values = [
            'class' => get_class($node),
        ];

        if (property_exists($node, 'name')) {
            $values['name'] = $node->name;
        }

        if (property_exists($node, 'value')) {
            $values['value'] = $node->value;
        }

        if (method_exists($node, 'getParams')) {
            $values['params'] = $node->getParams();
        }


        dump($node);

        if ($node instanceof MethodCall) {
            // dump($node);
            // dump($values);
        }

        // if ($node instanceof Namespace_) {
        //     CallStore::startNamespace($node->name->toString());
        // }

        // if ($node instanceof Class_) {
        //     CallStore::startClass($node->name);
        // }

        // if ($node instanceof PropertyProperty) {
        //     CallStore::addProperty($node->name);
        // }

        // if ($node instanceof ClassMethod) {
        //     CallStore::addMethod($node->name);
        // }
    }

    public function leaveNode(Node $node)
    {
    }

    public function beforeTraverse(array $nodes)
    {
        // dump($nodes);
    }
}
