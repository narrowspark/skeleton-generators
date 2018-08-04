<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

use Cake\Chronos\Chronos;
use Narrowspark\Automatic\Common\Generator\AbstractGenerator as BaseAbstractConfigurator;
use Narrowspark\Automatic\Common\Traits\ExpandTargetDirTrait;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractGenerator extends BaseAbstractConfigurator
{
    use ExpandTargetDirTrait;

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
        parent::__construct($filesystem, $options);

        $this->resourcePath = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'Resource';

        $storagePath   = self::expandTargetDir($this->options, '%STORAGE_DIR%');
        $testsPath     = self::expandTargetDir($this->options, '%TESTS_DIR%');
        $resourcesPath = self::expandTargetDir($this->options, '%RESOURCES_DIR%');
        $routesPath    = self::expandTargetDir($this->options, '%ROUTES_DIR%');
        $publicPath    = self::expandTargetDir($this->options, '%PUBLIC_DIR%');
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
            'public'    => $publicPath,
        ];
    }

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
    protected function getBasicDirectories(): array
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
    protected function getBasicFiles(): array
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
        return <<<'PHP'
<?php
declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
}
PHP;
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
        return (string) \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'phpunit.xml.template');
    }
}
