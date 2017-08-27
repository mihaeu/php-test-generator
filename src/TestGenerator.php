<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\Parser;

class TestGenerator
{
    /** @var Parser */
    private $parser;

    /** @var ClassAnalyser */
    private $classAnalyser;

    /** @var NodeTraverser */
    private $nodeTraverser;

    /** @var TwigRenderer */
    private $twigRenderer;

    public function __construct(
        Parser $parser,
        ClassAnalyser $classAnalyser,
        NodeTraverser $nodeTraverser,
        TwigRenderer $twigRenderer
    ) {
        $this->parser = $parser;
        $this->classAnalyser = $classAnalyser;
        $this->nodeTraverser = $nodeTraverser;
        $this->nodeTraverser->addVisitor($this->classAnalyser);
        $this->twigRenderer = $twigRenderer;
    }

    public function run(PhpFile $file) : string
    {
        $nodes = $this->parser->parse($file->content());
        $this->nodeTraverser->traverse($nodes);

        if ($this->classAnalyser->getClass() === null) {
            return '';
        }

        return $this->twigRenderer->render(
            $this->classAnalyser->getClass(),
            $this->classAnalyser->getParameters()
        );
    }
}
