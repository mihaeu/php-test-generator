<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Twig_TemplateWrapper;

class TwigRenderer
{
    private const DEFAULT_TEMPLATE = 'phpunit.twig';

    /** @var Twig_TemplateWrapper */
    private $template;

    /** @var bool */
    private $php5;

    /** @var bool */
    private $phpunit5;

    public function __construct(\Twig_Environment $twig, bool $php5, bool $phpunit5)
    {
        $this->template = $twig->load(self::DEFAULT_TEMPLATE);
        $this->php5 = $php5;
        $this->phpunit5 = $phpunit5;
    }

    public function render(string $className, array $dependencies) : string
    {
        return $this->template->render([
            'class' => $className,
            'dependencies' => $dependencies,
            'php5' => $this->php5,
            'phpunit5' => $this->phpunit5,
        ]);
    }
}
