<?php

declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Controller;

use FINDOLOGIC\ExtendFinSearch\Export\DynamicProductGroupService;
use FINDOLOGIC\FinSearch\Controller\ExportController as OriginalExportController;
use FINDOLOGIC\FinSearch\Export\HeaderHandler;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;

class ExportController extends OriginalExportController
{
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger,
        RouterInterface $router,
        HeaderHandler $headerHandler,
        SalesChannelContextFactory $salesChannelContextFactory,
        CacheItemPoolInterface $cache
    ){
        parent::__construct(
            $logger,
            $router,
            $headerHandler,
            $salesChannelContextFactory,
            $cache
        );

        $container->set('fin_search.dynamic_product_group', $container->get(DynamicProductGroupService::class));
    }
}
