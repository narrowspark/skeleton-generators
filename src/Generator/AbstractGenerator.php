<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

use Cake\Chronos\Chronos;
use Narrowspark\Discovery\Common\Traits\ExpandTargetDirTrait;
use Nette\PhpGenerator\PhpNamespace;
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
    protected $resourcePath;

    /**
     * Basic functions for the generator classes.
     *
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param array                                    $options
     */
    public function __construct(Filesystem $filesystem, array $options)
    {
        $this->filesystem   = $filesystem;
        $this->options      = $options;
        $this->resourcePath = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'Resource';

        $storagePath   = self::expandTargetDir($this->options, '%STORAGE_DIR%');
        $testsPath     = self::expandTargetDir($this->options, '%TESTS_DIR%');
        $resourcesPath = self::expandTargetDir($this->options, '%RESOURCES_DIR%');
        $routesPath    = self::expandTargetDir($this->options, '%ROUTES_DIR%');
        $appPath       = self::expandTargetDir($this->options, '%APP_DIR%');

        $this->folderPaths = [
            'storage'   => $storagePath,
            'logs'      => $storagePath . \DIRECTORY_SEPARATOR . 'logs',
            'framework' => $storagePath . \DIRECTORY_SEPARATOR . 'framework',
            'tests'     => $testsPath,
            'unit'      => $testsPath . \DIRECTORY_SEPARATOR . 'Unit',
            'resources' => $resourcesPath,
            'views'     => $resourcesPath . \DIRECTORY_SEPARATOR . 'views',
            'lang'      => $resourcesPath . \DIRECTORY_SEPARATOR . 'lang',
            'routes'    => $routesPath,
            'app'       => $appPath,
        ];
    }

    /**
     * Returns the project type of the class.
     *
     * @return string
     */
    abstract public function projectType(): string;

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
     * @return array
     */
    abstract protected function getDependencies(): array;

    /**
     * @return array
     */
    abstract protected function getDevDependencies(): array;

    /**
     * List of narrowspark files and directories that should be removed.
     *
     * @return array
     */
    protected function clean(): array
    {
        $array = [];

        if (! self::$isTest) {
            $array[] = 'README.md';
        }

        return $array;
    }

    /**
     * Get basic folder paths.
     *
     * @return string[]
     */
    private function getBasicDirectories(): array
    {
        return [
            $this->folderPaths['logs'],
            $this->folderPaths['framework'],
            $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Provider',
            $this->folderPaths['tests'] . \DIRECTORY_SEPARATOR . 'Unit',
        ];
    }

    /**
     * Get basic files with content.
     *
     * @return array
     */
    private function getBasicFiles(): array
    {
        $array = [
            $this->folderPaths['logs'] . \DIRECTORY_SEPARATOR . '.gitignore'              => "!.gitignore\n",
            $this->folderPaths['framework'] . \DIRECTORY_SEPARATOR . '.gitignore'         => 'down' . \PHP_EOL,
            $this->folderPaths['tests'] . \DIRECTORY_SEPARATOR . 'AbstractTestCase.php'   => $this->generateAbstractTestCaseClass(),
            $this->folderPaths['tests'] . \DIRECTORY_SEPARATOR . 'bootstrap.php'          => $this->generateBootstrapFile(),
        ];

        if (! self::$isTest) {
            $array['phpunit.xml'] = $this->getPhpunitXml();
        }

        return $array;
    }

    /**
     * Generate the AbstractTestCase class.
     *
     * @return string
     */
    private function generateAbstractTestCaseClass(): string
    {
        $namespace = new PhpNamespace('Tests');
        $namespace->addUse('PHPUnit\Framework\TestCase', 'BaseTestCase');

        $class = $namespace->addClass('AbstractTestCase');
        $class->setAbstract()
            ->setExtends('PHPUnit\Framework\TestCase');

        return '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . $namespace->__toString();
    }

    /**
     * Generate the bootstrap file.
     *
     * @return string
     */
    private function generateBootstrapFile(): string
    {
        $fileContent = <<<PHP
<?php
declare(strict_types=1);

require_once \realpath(__DIR__) . '/vendor/autoload.php';

/*
 |--------------------------------------------------------------------------
 | Set The Default Timezone
 |--------------------------------------------------------------------------
 |
 | Here we will set the default timezone for PHP. PHP is notoriously mean
 | if the timezone is not explicitly set. This will be used by each of
 | the PHP date and date-time functions throughout the application.
 |
 */
\\date_default_timezone_set(\\'UTC\\');

PHP;

        if (\class_exists(Chronos::class)) {
            $fileContent .= <<<'PHP'
\Cake\Chronos\Chronos::setTestNow(Chronos::now());
\Cake\Chronos\MutableDateTime::setTestNow(MutableDateTime::now());
\Cake\Chronos\Date::setTestNow(Date::now());
\Cake\Chronos\MutableDate::setTestNow(MutableDate::now());

PHP;
        }

        return $fileContent;
    }

    /**
     * Get the phpunit.xml content.
     *
     * @return string
     */
    private function getPhpunitXml(): string
    {
        return \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'phpunit.xml.template');
    }
}
