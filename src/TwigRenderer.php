<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Twig_TemplateWrapper;

class TwigRenderer
{
    private const DEFAULT_TEMPLATE = 'phpunit.twig';

    /** @var Twig_TemplateWrapper */
    private $template;

    public function __construct(\Twig_Environment $twig)
    {
        $this->template = $twig->load(self::DEFAULT_TEMPLATE);
    }

    public function render(string $className, array $dependencies) : string
    {
        return $this->template->render([
            'class' => $className,
            'dependencies' => $dependencies,
        ]);
    }
}
