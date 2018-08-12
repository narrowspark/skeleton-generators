<?php
declare(strict_types=1);
namespace Narrowspark\Skeleton\Generator;

use Narrowspark\Automatic\Common\Contract\Generator\DefaultGenerator as DefaultGeneratorContract;
use Narrowspark\Skeleton\Generator\Traits\HttpTypeTrait;

final class FrameworkGenerator extends ConsoleGenerator implements DefaultGeneratorContract
{
    use HttpTypeTrait;

    /**
     * {@inheritdoc}
     */
    public function getSkeletonType(): string
    {
        return 'framework';
    }
}
