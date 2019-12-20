<?php declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch;

use FINDOLOGIC\FinSearch\FinSearch;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ExtendFinSearch extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        require_once $this->getBasePath() . '/vendor/autoload.php';
        parent::build($container);

        $activePlugins = $container->getParameter('kernel.active_plugins');
        if (!isset($activePlugins[FinSearch::class])) {
            return;
        }

        // Only load relevant classes if FinSearch is available
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        $loader->load('finsearch_services.xml');
    }

    /**
     * @throws Plugin\Exception\PluginNotInstalledException
     */
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        // Only install this plugin if FinSearch plugin is installed and active
        $activePlugins = $this->container->getParameter('kernel.active_plugins');

        if (!isset($activePlugins[FinSearch::class])) {
            throw new Plugin\Exception\PluginNotInstalledException('FinSearch');
        }
    }
}
