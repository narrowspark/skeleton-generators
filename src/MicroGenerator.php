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

    /**
     * {@inheritdoc}
     */
    protected function getBootstrapContent(): string
    {
        return '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . \PHP_EOL . 'return [' . \PHP_EOL . '    /** > app/bootstrap **/' . \PHP_EOL . '    \App\Console\Bootstrap\LoadConsoleCommand::class => [\'console\'],' . \PHP_EOL . '    \App\Http\Bootstrap\LoadRoutes::class => [\'console\'],' . \PHP_EOL . '    /** app/bootstrap < **/' . \PHP_EOL . '];' . \PHP_EOL;
    }
}
