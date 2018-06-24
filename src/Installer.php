<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;

final class Installer
{
    /**
     * A composer instance.
     *
     * @var \Composer\Composer
     */
    private $composer;

    /**
     * The composer io implementation.
     *
     * @var \Composer\IO\IOInterface
     */
    private $io;

    /**
     * @param \Composer\Composer       $composer
     * @param \Composer\IO\IOInterface $io
     */
    public function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    public function install(): void
    {
    }
}
