<?php
declare(strict_types=1);
namespace Narrowspark\Skeleton\Generator;

use Narrowspark\Skeleton\Generator\Traits\HttpTypeTrait;

final class MicroGenerator extends ConsoleGenerator
{
    use HttpTypeTrait;

    /**
     * {@inheritdoc}
     */
    public function getSkeletonType(): string
    {
        return 'micro';
    }
}
