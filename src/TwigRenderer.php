<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Twig_TemplateWrapper;

class TwigRenderer
{
    private const DEFAULT_TEMPLATE = 'phpunit.twig';

    /** @var Twig_TemplateWrapper */
    private $template;

    /** @var TemplateConfiguration */
    private $templateConfiguration;

    public function __construct(\Twig_Environment $twig, TemplateConfiguration $templateConfiguration)
    {
        $this->template = $twig->load(self::DEFAULT_TEMPLATE);
        $this->templateConfiguration = $templateConfiguration;
    }

    public function render(string $className, array $dependencies) : string
    {
        return $this->template->render([
            'class' => $className,
            'dependencies' => $dependencies,
        ] + $this->templateConfiguration->toArray());
    }
}
