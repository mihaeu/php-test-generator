<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Mihaeu\TestGenerator\Output\OutputProcessor;
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

    /** @var OutputProcessor */
    private $outputProcessor;

    public function __construct(
        Parser $parser,
        ClassAnalyser $classAnalyser,
        NodeTraverser $nodeTraverser,
        TwigRenderer $twigRenderer,
        OutputProcessor $outputProcessor
    ) {
        $this->parser = $parser;
        $this->classAnalyser = $classAnalyser;
        $this->nodeTraverser = $nodeTraverser;
        $this->nodeTraverser->addVisitor($this->classAnalyser);
        $this->twigRenderer = $twigRenderer;
        $this->outputProcessor = $outputProcessor;
    }

    public function run(PhpFile $file): void
    {
        $nodes = $this->parser->parse($file->content());
        $this->nodeTraverser->traverse($nodes);

        if ($this->classAnalyser->getClass() === null) {
            return;
        }

        $this->outputProcessor->write(
            $this->twigRenderer->render(
                $this->classAnalyser->getClass(),
                $this->classAnalyser->getParameters()
            )
        );
    }
}
