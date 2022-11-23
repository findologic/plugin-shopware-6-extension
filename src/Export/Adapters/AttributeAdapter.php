<?php

declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Export\Adapters;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Shopware6Common\Export\Adapters\AttributeAdapter as OriginalAttributeAdapter;
use Vin\ShopwareSdk\Data\Entity\Product\ProductEntity;

class AttributeAdapter extends OriginalAttributeAdapter
{
    public function adapt(ProductEntity $product): array
    {
        $attributes = parent::adapt($product);

//        $attributes[] = new Attribute(
//            'Some attribute name',
//            ['I am an attribute value!']
//        );

        return $attributes;
    }
}
