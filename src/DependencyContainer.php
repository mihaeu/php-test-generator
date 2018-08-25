<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Docopt\Response;
use Mihaeu\TestGenerator\Output\Exception\InvalidFileException;
use Mihaeu\TestGenerator\Output\OutputProcessor;
use Mihaeu\TestGenerator\Output\OutputProcessorFactory;
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

    /**
     * @return TestGenerator
     * @throws InvalidFileException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testGenerator(): TestGenerator
    {
        return new TestGenerator(
            $this->parser(),
            new ClassAnalyser(),
            $this->nodeTraverser(),
            $this->twigRenderer(),
            $this->outputProcessor()
        );
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
            ['autoescape' => false]
        );
        $twig->addFilter($this->lcfirstFilter());
        $twig->addFilter($this->isNullFilter());
        $twig->addFilter($this->transformClazzFilter($this->args['--subject-format'] ?: '%t'));
        $twig->addFilter($this->transformDependencyFilter($this->args['--field-format'] ?: '%n'));
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

    public function transformClazzFilter($format) : Twig_SimpleFilter
    {
        return new Twig_SimpleFilter('transformClazz', function ($x) use ($format) {
            return str_replace(
                ['%t', '%T', '%n', '%N'],
                [lcfirst($x), ucfirst($x), lcfirst($x), ucfirst($x)],
                $format
            );
        });
    }

    public function transformDependencyFilter($format) : Twig_SimpleFilter
    {
        return new Twig_SimpleFilter('transformDependency', function (Dependency $x) use ($format) {
            return str_replace(
                ['%t', '%T', '%n', '%N'],
                [lcfirst($x->type() ?: ''), ucfirst($x->type() ?: ''), lcfirst($x->name()), ucfirst($x->name())],
                $format
            );
        });
    }

    public function baseClass() : Clazz
    {
        return $this->args['--base-class']
            ? Clazz::fromFullyQualifiedNameString($this->args['--base-class'])
            : $this->defaultBaseClass();
    }

    /**
     * @throws InvalidFileException
     */
    private function outputProcessor(): OutputProcessor
    {
        return OutputProcessorFactory::create(
            $this->args['<file>'],
            $this->args['--src-base'] ?? null,
            $this->args['--test-base'] ?? null
        );
    }

    private function defaultBaseClass()
    {
        if ($this->args['--php5']) {
            return Clazz::fromFullyQualifiedNameString('PHPUnit_Framework_TestCase');
        }
        return Clazz::fromFullyQualifiedNameString('PHPUnit\Framework\TestCase');
    }
}
