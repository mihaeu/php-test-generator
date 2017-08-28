<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Docopt\Response;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;

class DependencyContainer
{
    /** @var Response */
    private $args;

    public function __construct(Response $args)
    {
        $this->args = $args;
    }

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

    public function templateConfiguration() : TemplateConfiguration
    {
        return new TemplateConfiguration(
            $this->baseClass(),
            $this->args['--php5'],
            $this->args['--phpunit5'],
            $this->args['--mockery'],
            $this->args['--covers']
        );
    }

    public function twigRenderer() : TwigRenderer
    {
        return new TwigRenderer($this->twigEnvironment(), $this->templateConfiguration());
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

    public function baseClass() : Clazz
    {
        return $this->args['--base-class']
            ? Clazz::fromFullyQualifiedNameString($this->args['--base-class'])
            : $this->defaultBaseClass();
    }

    private function defaultBaseClass()
    {
        if ($this->args['--php5']) {
            return Clazz::fromFullyQualifiedNameString('PHPUnit_Framework_TestCase');
        }
        return Clazz::fromFullyQualifiedNameString('PHPUnit\Framework\TestCase');
    }
}
