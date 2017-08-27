<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;

class TestGenerator
{
    /** @var Parser */
    private $parser;

    /** @var ClassAnalyser */
    private $classAnalyser;

    /** @var NodeTraverser */
    private $nodeTraverser;

    public function __construct(
        Parser $parser,
        NameResolver $nameResolver,
        ClassAnalyser $classAnalyser,
        NodeTraverser $nodeTraverser
    ) {
        $this->parser = $parser;
        $this->classAnalyser = $classAnalyser;
        $this->nodeTraverser = $nodeTraverser;
        $this->nodeTraverser->addVisitor($nameResolver);
        $this->nodeTraverser->addVisitor($this->classAnalyser);
    }

    public function run(PhpFile $file) : string
    {
        $nodes = $this->parser->parse($file->content());
        $this->nodeTraverser->traverse($nodes);

        if ($this->classAnalyser->getClass() === null) {
            return '';
        }

        $template = phpunit6Template();
        return $template(
            $this->classAnalyser->getClass(),
            $this->classAnalyser->getParameters()
        );
    }
}
