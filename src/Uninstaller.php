<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator;

use Composer\Composer;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Repository\RepositoryInterface;

final class Uninstaller
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
     * Create a new Uninstaller instance.
     *
     * @param \Composer\Composer       $composer
     * @param \Composer\IO\IOInterface $io
     */
    public function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    public function uninstall(): void
    {
        $this->io->write(\sprintf('<info>Removing %s...</info>', Util::PLUGIN_NAME));
        $this->removePluginInstall();
        $this->removePluginFromComposer();
        $this->io->write('<info>    Complete!</info>');
    }

    /**
     * Remove the plugin installation itself.
     *
     * @return void
     */
    private function removePluginInstall(): void
    {
        $repository = $this->composer->getRepositoryManager()->getLocalRepository();
        $package    = $repository->findPackage(Util::PLUGIN_NAME, '*');

        if (! $package) {
            $this->io->write('<info>    Package not installed; nothing to do.</info>');

            return;
        }

        $this->composer->getInstallationManager()->uninstall($repository, new UninstallOperation($package));
        $this->io->write(\sprintf('<info>    Removed plugin %s.</info>', Util::PLUGIN_NAME));
        $this->updateLockFile($repository);
    }

    /**
     * Remove the plugin from the composer.json.
     *
     * @throws \Exception
     *
     * @return void
     */
    private function removePluginFromComposer(): void
    {
        $this->io->write('<info>    Removing from composer.json</info>');

        /** @var \Composer\Json\JsonFile $composerJson */
        [$composerJson] = Util::getComposerJsonFileAndManipulator();
        $json           = $composerJson->read();

        unset($json['require'][Util::PLUGIN_NAME]);

        $composerJson->write($json);
    }

    /**
     * Update the lock file.
     *
     * @param RepositoryInterface $repository
     */
    private function updateLockFile(RepositoryInterface $repository)
    {
        $locker      = $this->composer->getLocker();
        $allPackages = Collection::create($repository->getPackages())
            ->reject(function ($package) {
                return Util::PLUGIN_NAME === $package->getName();
            });
        $aliases = $allPackages->filter(function ($package) {
            return $package instanceof AliasPackage;
        });
        $devPackages = $allPackages->filter(function ($package) {
            return $package->isDev();
        });
        $packages = $allPackages->filter(function ($package) {
            return ! $package instanceof AliasPackage && ! $package->isDev();
        });

        $platformReqs    = $locker->getPlatformRequirements(false);
        $platformDevReqs = \array_diff($locker->getPlatformRequirements(true), $platformReqs);

        $result = $locker->setLockData(
            $packages->toArray(),
            $devPackages->toArray(),
            $platformReqs,
            $platformDevReqs,
            $aliases->toArray(),
            $locker->getMinimumStability(),
            $locker->getStabilityFlags(),
            $locker->getPreferStable(),
            $locker->getPreferLowest(),
            $locker->getPlatformOverrides()
        );

        if (! $result) {
            $this->io->write('<error>Unable to update lock file after removal of zend-skeleton-installer</error>');
        }
    }
}
