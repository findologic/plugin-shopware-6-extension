<?php declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Struct;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Property;
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
        array $customerGroups,
        Item $item
    ) {
        parent::__construct($product, $router, $container, $context, $shopkey, $customerGroups, $item);
    }

    protected function setProperties(): void
    {
        // Example of adding a new property:
//        $this->properties[] = new Property(
//            'Some property name',
//            ['I am a property value!']
//        );

        parent::setProperties();
    }

    protected function setAttributes(): void
    {
        // Example of adding a new attribute:
//        $this->attributes[] = new Attribute(
//            'Some attribute name',
//            ['I am an attribute value!']
//        );

        parent::setAttributes();
    }
}
