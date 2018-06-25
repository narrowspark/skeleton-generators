<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

abstract class AbstractHttpGenerator extends ConsoleGenerator
{
    /**
     * Returns all directories that should be generated.
     *
     * @return string[]
     */
    protected function getDirectories(): array
    {
        return \array_merge(
            parent::getDirectories(),
            [
                $this->folderPaths['public'],
                $this->folderPaths['tests'] . \DIRECTORY_SEPARATOR . 'Feature',
                $this->folderPaths['resources'],
                $this->folderPaths['views'],
                $this->folderPaths['lang'],
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Console',
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Http',
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Controller',
                $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR . 'Middleware',
            ]
        );
    }

    /**
     * Returns all files that should be generated.
     *
     * @return array
     */
    protected function getFiles(): array
    {
        $array    = parent::getFiles();
        $httpPath = $this->folderPaths['app'] . \DIRECTORY_SEPARATOR . 'Http' . \DIRECTORY_SEPARATOR;

        $array[$this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'api.php']             = '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL;
        $array[$this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'web.php']             = '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL;
        $array[$httpPath . 'Kernel.php']                                                    = $this->getHttpKernelClass();
        $array[$httpPath . 'Controller' . \DIRECTORY_SEPARATOR . 'AbstractController.php']  = $this->getControllerClass();
        $array[$this->folderPaths['public'] . \DIRECTORY_SEPARATOR . 'index.php']           = \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'index.php.template');

        if (! static::$isTest) {
            $array['phpunit.xml'] = $this->getPhpunitXmlContent();
        }

        return $array;
    }

    /**
     * Get the phpunit.xml content.
     *
     * @return string
     */
    protected function getPhpunitXmlContent(): string
    {
        $phpunitContent = \file_get_contents($this->resourcePath . \DIRECTORY_SEPARATOR . 'phpunit.xml.template');
        $feature        = "        <testsuite name=\"Feature\">\n            <directory suffix=\"Test.php\">./tests/Feature</directory>\n        </testsuite>\n";

        return $this->doInsertStringBeforePosition($phpunitContent, $feature, \mb_strpos($phpunitContent, '</testsuites>'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDependencies(): array
    {
        return [
            'cakephp/chronos'          => '^1.0.4',
            'narrowspark/http-emitter' => '^0.6.0',
            'narrowspark/http-status'  => '^4.0.0',
            'symfony/process'          => '^4.0.0',
            'viserio/http-factory'     => 'dev-master',
            'viserio/routing'          => 'dev-master',
        ];
    }

    /**
     * Insert string at specified position.
     *
     * @param string $string
     * @param string $insertStr
     * @param int    $position
     *
     * @return string
     */
    private function doInsertStringBeforePosition(string $string, string $insertStr, int $position): string
    {
        return \mb_substr($string, 0, $position) . $insertStr . \mb_substr($string, $position);
    }

    /**
     * Get the Http Kernel Class.
     *
     * @return string
     */
    private function getHttpKernelClass(): string
    {
        return <<<'PHP'
<?php
declare(strict_types=1);
namespace App\Http;

use Viserio\Component\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $middleware = [];
}

PHP;
    }

    /**
     * Get the Http Kernel Class.
     *
     * @return string
     */
    private function getControllerClass(): string
    {
        return <<<'PHP'
<?php
declare(strict_types=1);
namespace App\Http\Controller;

use Viserio\Component\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
}

PHP;
    }
}
