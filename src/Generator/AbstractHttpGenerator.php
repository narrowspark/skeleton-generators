<?php
declare(strict_types=1);
namespace Narrowspark\Project\Configurator\Generator;

use Nette\PhpGenerator\PhpNamespace;

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

        $array[$this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'api.php'] = '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL;
        $array[$this->folderPaths['routes'] . \DIRECTORY_SEPARATOR . 'web.php'] = '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL;
        $array[$httpPath . 'Kernel.php']                                        = $this->getHttpKernelClass();
        $array[$httpPath . 'Controller' . \DIRECTORY_SEPARATOR . 'Kernel.php']  = $this->getControllerClass();

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
        $namespace = new PhpNamespace('App\Http');
        $namespace->addUse('Viserio\Component\Foundation\Http\Kernel', 'HttpKernel');

        $class = $namespace->addClass('Kernel');
        $class->setFinal()
            ->setExtends('HttpKernel');
        $property = $class->addProperty('middlewareGroups', []);
        $property->setVisibility('protected')
            ->setComment("The application's route middleware groups.\n")
            ->setComment('@var array');
        $property2 = $class->addProperty('middleware', []);
        $property2->setVisibility('protected')
            ->setComment("The application's route middleware.\n")
            ->setComment('@var array');

        return '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . $class->__toString();
    }

    /**
     * Get the Http Kernel Class.
     *
     * @return string
     */
    private function getControllerClass(): string
    {
        $namespace = new PhpNamespace('App\Http\Controller');
        $namespace->addUse('Viserio\Component\Routing\Controller', 'BaseController');

        $class = $namespace->addClass('Controller');
        $class->setExtends('BaseController');

        return '<?php' . \PHP_EOL . 'declare(strict_types=1);' . \PHP_EOL . $class->__toString();
    }
}
