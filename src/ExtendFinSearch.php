<?php declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch;

use FINDOLOGIC\FinSearch\FinSearch;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Exception\PluginNotInstalledException;

class ExtendFinSearch extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        // Only install this plugin if FinSearch plugin is installed and active.
        if (!$this->isMainPluginActive()) {
            throw new PluginNotInstalledException('FinSearch');
        }
    }

    private function isMainPluginActive(): bool
    {
        $activePlugins = $this->container->getParameter('kernel.active_plugins');

        return isset($activePlugins[FinSearch::class]);
    }
}

// Uncomment this line if you require external dependencies for the extension plugin.
//require_once $this->getBasePath() . '/vendor/autoload.php';
