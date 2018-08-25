<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output\Exception;

class SubjectNotInSrcBaseException extends \InvalidArgumentException
{
    public function __construct(\SplFileInfo $subjectFile, \SplFileInfo $srcBase)
    {
        parent::__construct(
            sprintf(
                'Subject file "%s" is not in src base "%s"',
                $subjectFile->getRealPath(),
                $srcBase->getRealPath()
            )
        );
    }
}
