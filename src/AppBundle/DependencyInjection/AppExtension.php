<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
*/
class AppExtension extends Extension
{
    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $basePathToConfigFolder = __DIR__.'/../Resources/config';

        $getPaths = function ($isAddBasePath = true) use ($basePathToConfigFolder) {
            $paths = $isAddBasePath ? [$basePathToConfigFolder] : [];

            return $paths;
        };


        $loader = new Loader\YamlFileLoader($container, new FileLocator($getPaths()));

        foreach ($getPaths(true) as $path) {
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
                if ('routing.yml' === $fileInfo->getFilename()) {
                    continue;
                }
                $loader->load($fileInfo->getFilename());
            }
        }

    }
}
