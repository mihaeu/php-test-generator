<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;

class DependencyContainer
{
    public function nodeTraverser() : NodeTraverser
    {
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new NameResolver());
        return $nodeTraverser;
    }

    public function twigEnvironment() : Twig_Environment
    {
        $twig = new Twig_Environment(
            new Twig_Loader_Filesystem(__DIR__ . '/../templates'),
            [
                'autoescape' => false
            ]
        );
        $twig->addFilter($this->lcfirstFilter());
        $twig->addFilter($this->isNullFilter());
        return $twig;
    }

    public function twigRenderer() : TwigRenderer
    {
        return new TwigRenderer($this->twigEnvironment());
    }

    public function parser() : Parser
    {
        return (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    public function lcfirstFilter(): Twig_SimpleFilter
    {
        return new Twig_SimpleFilter('lcfirst', 'lcfirst');
    }

    public function isNullFilter(): Twig_SimpleFilter
    {
        return new Twig_SimpleFilter('isNull', function ($x) {
            return $x === null;
        });
    }
}
