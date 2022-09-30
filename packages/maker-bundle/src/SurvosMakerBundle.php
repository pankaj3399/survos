<?php

/*
 * This file is based on the Symfony MakerBundle package.
 */

namespace Survos\Bundle\MakerBundle;

use Survos\Bundle\MakerBundle\Command\MakeMethodCommand;
use Survos\Bundle\MakerBundle\DependencyInjection\Compiler\SurvosMakerCompilerPass;
use Survos\Bundle\MakerBundle\Maker\MakeBundle;
use Survos\Bundle\MakerBundle\Maker\MakeCrud;
use Survos\Bundle\MakerBundle\Maker\MakeInvokableCommand;
use Survos\Bundle\MakerBundle\Maker\MakeMenu;
use Survos\Bundle\MakerBundle\Maker\MakeMethod;
use Survos\Bundle\MakerBundle\Maker\MakeModel;
use Survos\Bundle\MakerBundle\Maker\MakeParamConverter;
use Survos\Bundle\MakerBundle\Maker\MakeService;
use Survos\Bundle\MakerBundle\Maker\MakeWorkflow;
use Survos\Bundle\MakerBundle\Maker\MakeWorkflowListener;
use Survos\Bundle\MakerBundle\Renderer\ParamConverterRenderer;
//use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\DoctrineAttributesCheckPass;
use Survos\DocBundle\Command\SurvosBuildDocsCommand;
use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\MakeCommandRegistrationPass;
use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\RemoveMissingParametersPass;
use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\SetDoctrineAnnotatedPrefixesPass;
//use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\SetDoctrineManagerRegistryClassPass;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class SurvosMakerBundle extends AbstractBundle implements CompilerPassInterface
{
    // The compiler pass
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('workflow.registry')) {
            return;
        }
//        $reference = new Reference('workflow.registry');

        $container->get(MakeWorkflowListener::class)
            ->setArgument('registry', new Reference('workflow.registry'))
        ;

    }


    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        foreach ([MakeMenu::class, MakeService::class, MakeMethod::class, MakeInvokableCommand::class, MakeModel::class] as $makerClass) {
            $builder->autowire($makerClass)
                ->addTag(MakeCommandRegistrationPass::MAKER_TAG) // 'maker.command'
                ->addArgument(new Reference('maker.generator'))
                ->addArgument($config['template_path'])
            ;
        }
        $builder->autowire(MakeBundle::class)
            ->addTag(MakeCommandRegistrationPass::MAKER_TAG) // 'maker.command'
            ->addArgument(new Reference('maker.generator'))
            ->addArgument($config['template_path'])
            ->addArgument($config['vendor'])
            ->addArgument($config['relative_bundle_path'])
            ->addArgument($config['bundle_name'])
        ;

        // we can likely combine these, or even move it to crud
        $builder->register('maker.param_converter_renderer', ParamConverterRenderer::class)
            ->addArgument(new Reference('maker.generator'))
            ->addArgument($config['template_path']);

        $builder->autowire(MakeParamConverter::class)
            ->addTag('maker.command')
            ->addArgument(new Reference('maker.doctrine_helper'))
//                ->addArgument(new Reference('maker.generator'))
            ->addArgument(new Reference('maker.param_converter_renderer'))
            ->addArgument($config['template_path'])
            ->addArgument(new Reference('parameter_bag'))

        ;

        $builder->autowire(MakeCrud::class)
            ->addTag('maker.command')
            ->addArgument(new Reference('maker.doctrine_helper'))
            ->addArgument(new Reference('maker.renderer.form_type_renderer'))
        ;

        $definition = $builder->autowire(MakeWorkflowListener::class)
            ->addTag('maker.command')
            ->addArgument(new Reference('maker.doctrine_helper'))
            ->addArgument(new Reference('maker.generator'))
        ;

        $builder->autowire(MakeMethodCommand::class)
            ->addTag('console.command')
            ->addMethodCall('setInvokeContainer', [new Reference('service_container')])
        ;


//        dd(service('maker.doctrine_helper')->nullOnInvalid(), service('workflow.registry')->nullOnInvalid(), service('x')->nullOnInvalid());
//            $definition
//                ->addArgument(new Reference('workflow.registry'))
//            ;
//        try {
//        } catch (\Exception $exception) {
//            // there must be a better way to only wire this if it exists.
//        }


        $builder->autowire(MakeWorkflow::class)
            ->addTag('maker.command')
            ->addArgument(new Reference('maker.doctrine_helper'))
            ->addArgument($config['template_path'])
        ;
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->scalarNode('template_path')->defaultValue(__DIR__ . '/../templates/skeleton/')->end()
            ->scalarNode('vendor')->defaultValue('Survos')->end()
            ->scalarNode('bundle_name')->defaultValue('FooBundle')->end()
            ->scalarNode('relative_bundle_path')->defaultValue('lib/temp/src')->end()
            ->end();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // add a priority so we run before the core command pass
        //        $container->addCompilerPass(new DoctrineAttributesCheckPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 11);
        $container->addCompilerPass(new MakeCommandRegistrationPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
        $container->addCompilerPass(new RemoveMissingParametersPass());
        //        $container->addCompilerPass(new SetDoctrineManagerRegistryClassPass());
        $container->addCompilerPass(new SetDoctrineAnnotatedPrefixesPass());


        // Register this class as a pass, to eliminate the need for the extra DI class
        // https://stackoverflow.com/questions/73814467/how-do-i-add-a-twig-global-from-a-bundle-config
        $container->addCompilerPass($this);

        //        dump(__FILE__, __LINE__);
        //        $container->addCompilerPass(new SurvosMakerCompilerPass());
    }
}
