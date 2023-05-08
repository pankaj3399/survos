<?php

namespace Survos\ApiGrid;

use Survos\ApiGrid\Api\Filter\FacetsFieldSearchFilter;
use Survos\ApiGrid\Api\Filter\MultiFieldSearchFilter;
use Survos\ApiGrid\Filter\MeiliSearch\MultiFieldSearchFilter as MeiliMultiFieldSearchFilter;
use Survos\ApiGrid\Components\ApiGridComponent;
use Survos\ApiGrid\Paginator\SlicePaginationExtension;
use Survos\ApiGrid\Service\DatatableService;
use Survos\ApiGrid\Twig\TwigExtension;
use Survos\GridGroupBundle\Service\GridGroupService;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Environment;
use Survos\ApiGrid\Filter\MeiliSearch\SortFilter;
use Survos\ApiGrid\Filter\MeiliSearch\DataTableFilter;
use Survos\ApiGrid\Filter\MeiliSearch\DataTableFacetsFilter;
use Survos\ApiGrid\State\MeilliSearchStateProvider;
use Survos\ApiGrid\Hydra\Serializer\DataTableCollectionNormalizer;
use ApiPlatform\Hydra\Serializer\PartialCollectionViewNormalizer;

class SurvosApiGridBundle extends AbstractBundle
{
    // $config is the bundle Configuration that you usually process in ExtensionInterface::load() but already merged and processed
    /**
     * @param array<mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        $builder->register(DataTableFilter::class)
            ->setAutowired(true)
            ->addTag('meilli_search_filter')
        ;
        $builder->register(MeiliMultiFieldSearchFilter::class)
            ->setAutowired(true)
            ->addTag('meilli_search_filter')
        ;
        $builder->register(DataTableFacetsFilter::class)
            ->setAutowired(true)
            ->addTag('meilli_search_filter')
        ;

        $builder->register(FacetsFieldSearchFilter::class)
            ->setAutowired(true)
            ->addTag('meilli_search_filter')
        ;


        $builder->register(SortFilter::class)
            ->setAutowired(true)
            ->addTag('meilli_search_filter')
        ;

        $services = $builder->findTaggedServiceIds('meilli_search_filter');
        $builder->register(MeilliSearchStateProvider::class)
            ->setArgument('$meilliSearchFilter', array_map(function ($serviceId) {
                    return new Reference($serviceId);
                }, array_keys($services))
            )
            ->setAutowired(true)
            ->addTag('api_platform.state_provider')
            ->setPublic(true);

        $builder->register('api_platform.hydra.normalizer.collection', DataTableCollectionNormalizer::class)
                ->setArgument('$contextBuilder', new Reference('api_platform.jsonld.context_builder'))
                ->setArgument('$resourceClassResolver', new Reference('api_platform.resource_class_resolver'))
                ->setArgument('$iriConverter', new Reference('api_platform.iri_converter'))
                ->setArgument('$resourceMetadataCollectionFactory', null)
                ->addTag('serializer.normalizer', ['priority' => -985]);

//        $container->services()->alias(MeiliCollectionNormalizer::class,'api_platform.hydra.normalizer.collection');

        // $builder->register('api_platform.hydra.normalizer.partial_collection_view', PartialCollectionViewNormalizer::class)
        //     ->setArgument('$collectionNormalizer', new Reference('api_platform.hydra.normalizer.partial_collection_view.inner'))
        //     ->setArgument('$pageParameterName', new Reference('api_platform.collection.pagination.page_parameter_name'))
        //     ->setArgument('$enabledParameterName', new Reference('api_platform.collection.pagination.enabled_parameter_name'))
        //     ->setArgument('$resourceMetadataFactory', new Reference('api_platform.metadata.resource.metadata_collection_factory'))
        //     ->setArgument('$propertyAccessor', new Reference('api_platform.property_accessor'))
        //     ->setPublic(false)
        //     ->setDecoratedService(MeiliCollectionNormalizer::class);

        $builder->register('api_platform.doctrine.orm.query_extension.pagination',SlicePaginationExtension::class)
            ->setAutowired(true)
            ->addTag('api_platform.doctrine.orm.query_extension.collection', ['priority' => -60])
        ;
        $services = $container->services();
        $services->set(SlicePaginationExtension::class)
            ->tag('api_platform.doctrine.orm.query_extension.collection', ['priority' => -60])
        ;
        $container->services()->alias(SlicePaginationExtension::class,'api_platform.doctrine.orm.query_extension.pagination');

        if (class_exists(Environment::class) && class_exists(StimulusTwigExtension::class)) {
            $builder
                ->setDefinition('survos.api_grid_bundle', new Definition(TwigExtension::class))
                ->addTag('twig.extension')
                ->setPublic(false);
        }

        $builder->register(DatatableService::class)->setAutowired(true)->setAutoconfigured(true);

        $builder->register(ApiGridComponent::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setArgument('$twig', new Reference('twig'))
            ->setArgument('$logger', new Reference('logger'))
            ->setArgument('$datatableService', new Reference(DatatableService::class))
            ->setArgument('$stimulusController', $config['stimulus_controller']);
        $builder->register(MultiFieldSearchFilter::class)
            ->addArgument(new Reference('doctrine.orm.default_entity_manager'))
            ->addArgument(new Reference('request_stack'))
            ->addArgument(new Reference('logger'))
            ->addTag('api_platform.filter');

        //        $builder->register(GridComponent::class);
        //        $builder->autowire(GridComponent::class);

        //        $definition->setArgument('$widthFactor', $config['widthFactor']);
        //        $definition->setArgument('$height', $config['height']);
        //        $definition->setArgument('$foregroundColor', $config['foregroundColor']);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        // since the configuration is short, we can add it here
        $definition->rootNode()
            ->children()
            ->scalarNode('stimulus_controller')->defaultValue('@survos/api-grid-bundle/api_grid')->end()
            ->scalarNode('widthFactor')->defaultValue(2)->end()
            ->scalarNode('height')->defaultValue(30)->end()
            ->scalarNode('foregroundColor')->defaultValue('green')->end()
            ->end();;
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $configs = $builder->getExtensionConfig('api_platform');
        //        dd($configs);
        //        assert($configs[0]['defaults']['pagination_client_items_per_page'], "pagination_client_items_per_page must be tree in config/api_platform");

        // https://stackoverflow.com/questions/72507212/symfony-6-1-get-another-bundle-configuration-data/72664468#72664468
        //        // iterate in reverse to preserve the original order after prepending the config
        //        foreach (array_reverse($configs) as $config) {
        //            $container->prependExtensionConfig('my_maker', [
        //                'root_namespace' => $config['root_namespace'],
        //            ]);
        //        }
    }
}
