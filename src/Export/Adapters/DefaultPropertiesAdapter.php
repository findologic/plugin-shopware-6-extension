<?php

declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Export\Adapters;

use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\FinSearch\Export\Adapters\DefaultPropertiesAdapter as OriginalDefaultPropertiesAdapter;
use Shopware\Core\Content\Product\ProductEntity;

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
