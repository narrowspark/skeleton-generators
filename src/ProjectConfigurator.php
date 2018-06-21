<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;
use Narrowspark\Discovery\Common\Configurator\AbstractConfigurator;
use Narrowspark\Discovery\Common\Contract\Package;

final class ProjectConfigurator extends AbstractConfigurator
{
    /**
     * This should be only used if this class is tested.
     *
     * @internal
     *
     * @var bool
     */
    public static $isTest = false;

    /**
     * Path to the resource dir.
     *
     * @var string
     */
    private $resourcePath;

    /**
     * @var string
     */
    private static $question = '    Please select a project type:
    [<comment>c</comment>] console-framework
    [<comment>f</comment>] full-stack framework
    [<comment>m</comment>] micro-framework
    (defaults to <comment>f</comment>): ';

    /**
     * {@inheritdoc}
     */
    public function __construct(Composer $composer, IOInterface $io, array $options = [])
    {
        parent::__construct($composer, $io, $options);

        $this->resourcePath = __DIR__ . '/../../Resource';
    }

    /**
     * Return the configurator key name.
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'narrowspark-project';
    }

    /**
     * Configure the application after the package settings.
     *
     * @param \Narrowspark\Discovery\Common\Contract\Package $package
     *
     * @return void
     */
    public function configure(Package $package): void
    {
        // TODO: Implement configure() method.
    }

    /**
     * Unconfigure the application after the package settings.
     *
     * @param \Narrowspark\Discovery\Common\Contract\Package $package
     *
     * @return void
     */
    public function unconfigure(Package $package): void
    {
        $this->write('Project cant be unconfigured');
    }
}