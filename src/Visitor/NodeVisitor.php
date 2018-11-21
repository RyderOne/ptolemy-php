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
        if ($node instanceof MethodCall) {
            CallStore::handleMethodCall($node->var->name, $node->name);
        }

        if ($node instanceof Namespace_) {
            CallStore::startNamespace($node->name->toString());
        }

        if ($node instanceof Class_ || $node instanceof Node\Stmt\Interface_) {
            CallStore::startClass($node->name);
        }

        if ($node instanceof PropertyProperty) {
            CallStore::addProperty($node->name);
        }

        if ($node instanceof ClassMethod) {
            CallStore::startMethod($node->name, $node->params);
        }
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof ClassMethod) {
            CallStore::endMethod();
        }

        if ($node instanceof Namespace_) {
            CallStore::endNamespace();
        }

        if ($node instanceof Class_ || $node instanceof Node\Stmt\Interface_) {
            CallStore::endClass();
        }
    }
}
