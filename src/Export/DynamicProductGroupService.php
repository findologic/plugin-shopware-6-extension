<?php

declare(strict_types=1);

namespace FINDOLOGIC\ExtendFinSearch\Export;

use FINDOLOGIC\FinSearch\Export\DynamicProductGroupService as OriginalDynamicProductGroupService;
use FINDOLOGIC\FinSearch\Utils\Utils;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\ProductStream\Service\ProductStreamBuilder;
use Shopware\Core\Content\ProductStream\Service\ProductStreamBuilderInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DynamicProductGroupService extends OriginalDynamicProductGroupService
{
    private const CACHE_ID_PRODUCT_GROUP = 'fl_product_groups';
    private const CACHE_LIFETIME_PRODUCT_GROUP = 60 * 11;

    /**
     * @var ProductStreamBuilderInterface
     */
    protected $productStreamBuilder;

    /**
     * @var EntityRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var CacheItemPoolInterface
     */
    protected $cache;

    /**
     * @var string
     */
    protected $shopkey;

    /**
     * @var int
     */
    protected $start;

    /**
     * @var SalesChannelEntity
     */
    private $salesChannel;

    /**
     * @var EntityRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        ContainerInterface $container,
        CacheItemPoolInterface $cache,
        RequestStack $requestStack
    ) {
        $request = $requestStack->getCurrentRequest();

        $this->container = $container;
        $this->cache = $cache;
        $this->shopkey = $request->query->get('shopkey');
        $this->start = $request->query->get('start', 0);
        $this->productStreamBuilder = $container->get(ProductStreamBuilder::class);
        $this->productRepository = $container->get('product.repository');
        $this->categoryRepository = $container->get('category.repository');
        $this->context = Context::createDefaultContext();
    }

    public function setSalesChannel(SalesChannelEntity $salesChannelEntity): void
    {
        $this->salesChannel = $salesChannelEntity;
    }

    public function warmUp(): void
    {
        $cacheItem = $this->getCacheItem();
        $products = $this->parseProductGroups();
        if (Utils::isEmpty($products)) {
            return;
        }
        $cacheItem->set(serialize($products));
        $cacheItem->expiresAfter(self::CACHE_LIFETIME_PRODUCT_GROUP);
        $this->cache->save($cacheItem);
    }

    public function isWarmedUp(): bool
    {
        if ($this->start === 0) {
            return false;
        }

        $cacheItem = $this->getCacheItem();
        if ($cacheItem && $cacheItem->isHit()) {
            $cacheItem->expiresAfter(self::CACHE_LIFETIME_PRODUCT_GROUP);
            $this->cache->save($cacheItem);

            return true;
        }

        return false;
    }

    private function parseProductGroups(): array
    {
        $criteria = $this->buildCriteria();

        /** @var CategoryCollection $categories */
        $categories = $this->categoryRepository->search($criteria, $this->context)->getEntities();

        if ($categories === null || empty($categories->getElements())) {
            return [];
        }

        $products = [];
        foreach ($categories->getElements() as $categoryEntity) {
            $productStream = $categoryEntity->getProductStream();

            if (!$productStream) {
                continue;
            }

            $filters = $this->productStreamBuilder->buildFilters(
                $productStream->getId(),
                $this->context
            );

            $criteria = new Criteria();
            $criteria->addFilter(...$filters);
            $productIds = $this->productRepository->searchIds($criteria, $this->context)->getIds();
            foreach ($productIds as $productId) {
                $products[$productId][] = $categoryEntity->getId();
            }
        }

        return $products;
    }

    /**
     * @return CategoryEntity[]
     */
    public function getCategories(string $productId): array
    {
        $categoryIds = [];
        $cacheItem = $this->getCacheItem();
        if ($cacheItem->isHit()) {
            $categoryIds = unserialize($cacheItem->get());
        }
        if (!Utils::isEmpty($categoryIds) && isset($categoryIds[$productId])) {
            $criteria = $this->buildCriteria($categoryIds[$productId]);

            return $this->categoryRepository->search($criteria, $this->context)->getElements();
        }

        return [];
    }

    private function buildCriteria($ids = []): Criteria
    {
        $mainCategoryId = $this->salesChannel->getNavigationCategoryId();

        $criteria = new Criteria($ids);
        $criteria->addFilter(new ContainsFilter('path', $mainCategoryId));
        $criteria->addAssociation('seoUrls');
        $criteria->addAssociation('productStream');
        $criteria->addFilter(
            new NotFilter(
                NotFilter::CONNECTION_AND,
                [new EqualsFilter('productStreamId', null)]
            )
        );

        return $criteria;
    }

    private function getCacheItem(): CacheItemInterface
    {
        $id = sprintf('%s_%s', self::CACHE_ID_PRODUCT_GROUP, $this->shopkey);

        return $this->cache->getItem($id);
    }
}
