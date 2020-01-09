<?php declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Struct;

use FINDOLOGIC\FinSearch\Struct\FindologicProduct as OriginalFindologicProduct;
use Psr\Container\ContainerInterface;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\Context;
use Symfony\Component\Routing\RouterInterface;

class FindologicProduct extends OriginalFindologicProduct
{
    public function __construct(
        ProductEntity $product,
        RouterInterface $router,
        ContainerInterface $container,
        Context $context,
        string $shopkey,
        array $customerGroups
    ) {
        parent::__construct($product, $router, $container, $context, $shopkey, $customerGroups);
    }

    /*
     * Example on how to add custom properties to the product
     */
    protected function setProperties(): void
    {
        parent::setProperties();
    }

    /**
     * @inheritDoc
     */
    protected function setAttributes(): void
    {
        parent::setAttributes();
    }
}
