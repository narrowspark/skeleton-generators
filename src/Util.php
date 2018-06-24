<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator;

use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Json\JsonManipulator;

final class Util
{
    public const PLUGIN_NAME = 'narrowspark/project-configurator';

    /**
     * Return the composer json file and json manipulator.
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public static function getComposerJsonFileAndManipulator(): array
    {
        $json        = new JsonFile(Factory::getComposerFile());
        $manipulator = new JsonManipulator(\file_get_contents($json->getPath()));

        return [$json, $manipulator];
    }

    /**
     * Get the composer.lock file path.
     *
     * @return string
     */
    public static function getComposerLockFile(): string
    {
        return \mb_substr(Factory::getComposerFile(), 0, -4) . 'lock';
    }
}
