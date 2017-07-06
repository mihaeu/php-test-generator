<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class TestGenerator
{
    /** @var Parser */
    private $parser;

    /** @var NameResolver */
    private $nameResolver;

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
        $this->nameResolver = $nameResolver;
        $this->classAnalyser = $classAnalyser;
        $this->nodeTraverser = $nodeTraverser;
        $this->nodeTraverser->addVisitor($this->nameResolver);
        $this->nodeTraverser->addVisitor($this->classAnalyser);
    }

    public function run(PhpFile $file) : string
    {
        $nodes = $this->parser->parse($file->content());
        $this->nodeTraverser->traverse($nodes);

        return phpunit6Template('A');
    }
}

