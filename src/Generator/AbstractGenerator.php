<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

use Narrowspark\Discovery\Common\Traits\ExpandTargetDirTrait;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractGenerator
{
    use ExpandTargetDirTrait;

    /**
     * This should be only used if this class is tested.
     *
     * @internal
     *
     * @var bool
     */
    public static $isTest = false;

    /**
     * A Filesystem instance.
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The composer extra options data.
     *
     * @var array
     */
    protected $options;

    /**
     * Default folder paths.
     *
     * @var array
     */
    protected $folderPaths;

    /**
     * Path to the resource dir.
     *
     * @var string
     */
    private $resourcePath;

    /**
     * Basic functions for the generator classes.
     *
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param array                                    $options
     */
    public function __construct(Filesystem $filesystem, array $options)
    {
        $this->filesystem = $filesystem;
        $this->options    = $options;
        $this->resourcePath = __DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'Resource';

        $storagePath = self::expandTargetDir($this->options, '%STORAGE_DIR%');
        $testsPath   = self::expandTargetDir($this->options, '%TESTS_DIR%');

        $this->folderPaths = [
            'storage'   => $storagePath,
            'logs'      => $storagePath . DIRECTORY_SEPARATOR . 'logs',
            'framework' => $storagePath . DIRECTORY_SEPARATOR . 'framework',
            'tests' => $testsPath,
            'unit'  => $testsPath . DIRECTORY_SEPARATOR . 'Unit',
        ];
    }

    /**
     * Returns the project type of the class.
     *
     * @return string
     */
    abstract public function projectType(): string;

    /**
     * Returns all directories that should be generated.
     *
     * @return string[]
     */
    abstract protected function getDirectories(): array;


    /**
     * Returns all files that should be generated.
     *
     * @return array
     */
    abstract protected function getFiles(): array;

    /**
     * List of narrowspark files and directories that should be removed.
     *
     * @return array
     */
    protected function clean(): array
    {
        $array = [];

        if (! self::$isTest && \file_exists('README.md')) {
            $array[] = 'README.md';
        }

        return $array;
    }

    /**
     * Generate the project.
     *
     * @return void
     */
    public function generate(): void
    {
        $this->filesystem->mkdir(\array_merge($this->getBasicDirectories(), $this->getDirectories()));

        $files = \array_merge($this->getBasicFiles(), $this->getFiles());

        foreach ($files as $filePath => $fileContent) {
            $this->filesystem->dumpFile($filePath, $fileContent);
        }

        $this->filesystem->remove($this->clean());
    }

    /**
     * Get basic folder paths.
     *
     * @return string[]
     */
    private function getBasicDirectories(): array
    {
        return [
            $this->folderPaths['logs'] . DIRECTORY_SEPARATOR . '.gitignore',
            $this->folderPaths['framework'] . DIRECTORY_SEPARATOR . '.gitignore',
        ];
    }

    /**
     * Get basic files with content.
     *
     * @return array
     */
    private function getBasicFiles(): array
    {
        return [
            $this->folderPaths['logs'] . DIRECTORY_SEPARATOR . '.gitignore' => "!.gitignore\n",
            $this->folderPaths['framework'] . DIRECTORY_SEPARATOR . '.gitignore' => 'down' . \PHP_EOL,
            $this->folderPaths['tests'] . DIRECTORY_SEPARATOR . 'AbstractTestCase.php' => \file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'AbstractTestCase.php.template'),
            $this->folderPaths['tests'] . DIRECTORY_SEPARATOR . 'bootstrap.php.template'
        ];
    }
}