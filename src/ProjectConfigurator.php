<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\Event as ScriptEvent;
use Composer\Script\ScriptEvents;
use Narrowspark\Project\Configurator\Generator\ConsoleGenerator;
use Narrowspark\Project\Configurator\Generator\FrameworkGenerator;
use Narrowspark\Project\Configurator\Generator\MicroGenerator;
use Symfony\Component\Filesystem\Filesystem;

final class ProjectConfigurator implements PluginInterface, EventSubscriberInterface
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
     * Check if the the plugin is activated.
     *
     * @var bool
     */
    private static $activated = true;

    /**
     * @var string
     */
    private static $question = '    Please select a project type:
    [<comment>c</comment>] console-framework
    [<comment>f</comment>] full-stack framework
    [<comment>m</comment>] micro-framework
    (defaults to <comment>f</comment>): ';

    /**
     * Key to class mapper.
     *
     * @var array
     */
    private static $class = [
        'f' => FrameworkGenerator::class,
        'c' => ConsoleGenerator::class,
        'm' => MicroGenerator::class,
    ];

    /**
     * Provide composer event listeners.
     *
     * This particular combination will ensure that the plugin works under each
     * of the following scenarios:
     *
     * - create-project
     * - install, with or without a composer.lock
     * - update, with or without a composer.lock
     *
     * After any of the above have run at least once, the plugin will uninstall
     * itself.
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        if (! self::$activated) {
            return [];
        }

        $subscribers = [
            ['installOptionalDependencies', 1024],
            ['uninstallPlugin'],
        ];

        return [
            ScriptEvents::POST_INSTALL_CMD        => $subscribers,
            ScriptEvents::POST_UPDATE_CMD         => $subscribers,
            ScriptEvents::POST_CREATE_PROJECT_CMD => ['onPostCreateProject', 1024],
        ];
    }

    /**
     * Activate the plugin.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        if (($errorMessage = $this->getErrorMessage()) !== null) {
            self::$activated = false;

            $io->writeError('<warning>Narrowspark Project-Configurator has been disabled. ' . $errorMessage . '</warning>');

            return;
        }

        $this->composer = $composer;
        $this->io       = $io;
    }

    /**
     * Execute on composer create project event.
     *
     * @param \Composer\Script\Event $event
     *
     * @throws \Exception
     */
    public function onPostCreateProject(Event $event): void
    {
        $answer = $this->io->askAndValidate(
            self::$question,
            [$this, 'validateProjectQuestionAnswerValue'],
            null,
            'f'
        );

        /** @var \Narrowspark\Project\Configurator\Generator\AbstractGenerator $generator */
        $generator = new self::$class[$answer](new Filesystem(), $this->composer->getPackage()->getExtra());
        $generator->generate();
    }

    /**
     * Install optional dependencies, if any.
     *
     * @param ScriptEvent $event
     */
    public function installOptionalDependencies(ScriptEvent $event)
    {
        (new Installer($this->composer, $this->io))->install();
    }

    /**
     * Remove the installer after project installation.
     *
     * @param ScriptEvent $event
     */
    public function uninstallPlugin(ScriptEvent $event)
    {
        (new Uninstaller($this->composer, $this->io))->uninstall();
    }

    /**
     * Validate given input answer.
     *
     * @param null|string $value
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function validateProjectQuestionAnswerValue(?string $value): string
    {
        if ($value === null) {
            return 'f';
        }

        $value = \mb_strtolower($value[0]);

        if (! \in_array($value, ['f', 'm', 'c'], true)) {
            throw new \InvalidArgumentException('Invalid choice.');
        }

        return $value;
    }

    /**
     * @codeCoverageIgnore
     *
     * Check if project-configurator can be activated.
     *
     * @return null|string
     */
    private function getErrorMessage(): ?string
    {
        $errorMessage = null;

        if (! \class_exists('\Narrowspark\Discovery')) {
            $errorMessage = 'This package only works with the [narrowspark/discovery] package. Please add [narrowspark/discovery] to your composer.json file.';
        }

        return $errorMessage;
    }
}
