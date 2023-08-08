<?php

namespace Survos\ApiGrid\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Meilisearch\Bundle\SearchService;
use Meilisearch\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Tagged;
use ApiPlatform\Util\Inflector;
use ApiPlatform\State\Pagination\Pagination;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;
use Meilisearch\Search\SearchResult;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;

class MeilliSearchStateProvider implements ProviderInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private EntityManagerInterface $em,
        private Pagination $pagination,
        private ServiceLocator $meilliSearchFilter,
        private string $meiliHost,
        private string $meiliKey,
        private readonly DenormalizerInterface $denormalizer
    )
    {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {

            $resourceClass = $operation->getClass();
            $body = [];

            foreach ($this->meilliSearchFilter->getProvidedServices() as $meilliSearchFilter) {
                $body = $this->meilliSearchFilter->get($meilliSearchFilter)->apply($body, $resourceClass, $operation, $context);
            }

            $searchQuery = isset($context['filters']['search'])?$context['filters']['search']:"";

            $body['limit'] = (int) $context['filters']['limit'] ??= $this->pagination->getLimit($operation, $context);
            $body['offset'] = (int) $context['filters']['offset'] ??= $this->pagination->getOffset($operation, $context);
            $body['attributesToHighlight'] = ['_translations'];
            $body['highlightPreTag'] = '<em class="bg-info">';
            $body['highlightPostTag'] =  '</em>';
//            dd($uriVariables, $context);
            $locale = $context['filters']['_locale'] ?? null;

            if (!$indexName = isset($context['uri_variables']['indexName'])?$context['uri_variables']['indexName']:false) {
                $indexName = $this->getSearchIndexObject($operation->getClass(), $locale);
            }
            // this seems problematic, since it's probably defined by the application, we're getting it again here.
            try {
            $client = new Client($this->meiliHost, $this->meiliKey);
            $index = $client->index($indexName);
            //dd($body);
            } catch (\Exception $exception) {
                throw new \Exception($index->getUid() . ' ' . $exception->getMessage());
            }
            $data = $index->search($searchQuery, $body);
            $data = $this->denormalizeObject($data, $resourceClass);
            unset($body['filter']);
            $body['limit'] = 0;
            $body['offset'] = 0;
            $facets = $index->search('', $body);

            return ['data' => $data, 'facets' => $facets];
        }

        return null;
    }

    private function getSearchIndexObject(string $class, ?string $locale=null) {
        $class = explode("\\",$class);
        return end($class);
    }

    private function denormalizeObject(SearchResult $data, $resourceClass) {
        $returnObject['offset'] = $data->getOffset();
        $returnObject['limit'] = $data->getLimit();
        $returnObject['estimatedTotalHits'] = $data->getEstimatedTotalHits();
        $returnObject['hits'] = $this->denormalizer->normalize($data->getHits(), 'meili');
        $returnObject['processingTimeMs'] = $data->getProcessingTimeMs();
        $returnObject['query'] = $data->getQuery();
        $returnObject['facetDistribution'] = $data->getFacetDistribution();
        $returnObject['facetStats'] = $data->getFacetStats();

        return new SearchResult($returnObject);
    }
}
