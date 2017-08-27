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

    public function render(Clazz $class, array $dependencies) : string
    {
        return $this->template->render(
            ['dependencies' => $dependencies]
            + $this->templateConfiguration->toArray()
            + $class->toArray()
        );
    }
}
