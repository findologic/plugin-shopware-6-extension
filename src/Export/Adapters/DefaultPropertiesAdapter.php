<?php

declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Export\Adapters;

use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Shopware6Common\Export\Adapters\DefaultPropertiesAdapter as OriginalDefaultPropertiesAdapter;
use Vin\ShopwareSdk\Data\Entity\Product\ProductEntity;

class DefaultPropertiesAdapter extends OriginalDefaultPropertiesAdapter
{
    public function adapt(ProductEntity $product): array
    {
        $properties = parent::adapt($product);

//        $properties[] = new Property(
//            'Some property name',
//            ['' => 'I am a property value!']
//        );

        return $properties;
    }
}
