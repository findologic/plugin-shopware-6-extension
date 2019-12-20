<?php declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch;

use FINDOLOGIC\FinSearch\FinSearch;
use Shopware\Core\Framework\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtendFinSearch extends Plugin
{
    /**
     * @throws Plugin\Exception\PluginNotInstalledException
     */
    public function build(ContainerBuilder $container): void
    {
        require_once $this->getBasePath() . '/vendor/autoload.php';
        parent::build($container);

        $activePlugins = $container->getParameter('kernel.active_plugins');
        if (!isset($activePlugins[FinSearch::class])) {
            throw new Plugin\Exception\PluginNotInstalledException('FinSearch');
        }
    }
}
