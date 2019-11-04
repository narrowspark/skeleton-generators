<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Narrowspark\Skeleton\Generator;

use const DIRECTORY_SEPARATOR;
use function array_merge;
use function file_get_contents;
use function mb_strpos;
use function mb_substr;

class HttpGenerator extends ConsoleGenerator
{
    /**
     * {@inheritdoc}
     */
    public function getSkeletonType(): string
    {
        return 'http';
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return array_merge(
            parent::getDependencies(),
            [
                'cakephp/chronos' => '^1.2.2',
                'narrowspark/http-emitter' => '^1.0.0',
                'viserio/http-foundation' => 'dev-master',
                'viserio/view' => 'dev-master',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): void
    {
        parent::generate();

        $this->filesystem->copy(
            $this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'favicon.ico',
            $this->folderPaths['public'] . DIRECTORY_SEPARATOR . 'favicon.ico'
        );
    }

    /**
     * Returns all directories that should be generated.
     *
     * @return string[]
     */
    protected function getDirectories(): array
    {
        return array_merge(
            parent::getDirectories(),
            [
                $this->folderPaths['public'],
                $this->folderPaths['tests'] . DIRECTORY_SEPARATOR . 'Feature',
                $this->folderPaths['resources'],
                $this->folderPaths['views'],
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Http',
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controller',
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middleware',
                $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Bootstrap',
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
        $httpPath = $this->folderPaths['app'] . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR;

        $files = array_merge(
            parent::getFiles(),
            [
                $this->folderPaths['routes'] . DIRECTORY_SEPARATOR . 'api.php' => '<?php' . "\n" . 'declare(strict_types=1);' . "\n\n" . '/** @var \Viserio\Component\Routing\Router $router */' . "\n",
                $this->folderPaths['routes'] . DIRECTORY_SEPARATOR . 'web.php' => $this->getWebRoutes(),
                $httpPath . 'Kernel.php' => $this->getHttpKernelClass(),
                $httpPath . 'Controller' . DIRECTORY_SEPARATOR . 'AbstractController.php' => $this->getControllerClass(),
                $httpPath . 'Bootstrap' . DIRECTORY_SEPARATOR . 'LoadRoutes.php' => $this->getLoadRoutesClass(),
                $this->folderPaths['public'] . DIRECTORY_SEPARATOR . 'index.php' => $this->getIndexFile(),
                $this->folderPaths['views'] . DIRECTORY_SEPARATOR . 'welcome.php' => $this->getWelcomeFile(),
            ]
        );

        if (! self::$isTest) {
            $files['phpunit.xml'] = $this->getPhpunitXmlContent();
        }

        return $files;
    }

    /**
     * Get the phpunit.xml content.
     *
     * @return string
     */
    protected function getPhpunitXmlContent(): string
    {
        $phpunitContent = (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'phpunit.xml.stub');
        $feature = "        <testsuite name=\"Feature\">\n            <directory suffix=\"Test.php\">./tests/Feature</directory>\n        </testsuite>\n";

        return $this->doInsertStringBeforePosition($phpunitContent, $feature, (int) mb_strpos($phpunitContent, '</testsuites>'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getBootstrapContent(): string
    {
        return '<?php' . "\n" . 'declare(strict_types=1);' . "\n\n" . 'return [' . "\n" . '    /** > app/bootstrap **/' . "\n" . '    \App\Console\Bootstrap\LoadConsoleCommand::class => [\'console\'],' . "\n" . '    \App\Http\Bootstrap\LoadRoutes::class => [\'http\'],' . "\n" . '    /** app/bootstrap < **/' . "\n" . '];' . "\n";
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
        return mb_substr($string, 0, $position) . $insertStr . mb_substr($string, $position);
    }

    /**
     * Get the http kernel class.
     *
     * @return string
     */
    private function getHttpKernelClass(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'HttpKernel.php.stub');
    }

    /**
     * Get the controller class.
     *
     * @return string
     */
    private function getControllerClass(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'AbstractController.php.stub');
    }

    /**
     * Get the load routes bootstrap class.
     *
     * @return string
     */
    private function getLoadRoutesClass(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'LoadRoutes.php.stub');
    }

    /**
     * Get the web file content.
     *
     * @return string
     */
    private function getWebRoutes(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'web.php.stub');
    }

    /**
     * Get the index file content.
     *
     * @return string
     */
    private function getIndexFile(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'index.php.stub');
    }

    /**
     * Get the welcome file content.
     *
     * @return string
     */
    private function getWelcomeFile(): string
    {
        return (string) file_get_contents($this->resourcePath . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'welcome.php.stub');
    }
}
