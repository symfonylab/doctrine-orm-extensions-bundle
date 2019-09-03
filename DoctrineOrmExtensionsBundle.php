<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle;

use SymfonyLab\CoreBundle\DependencyInjection\Compiler\GraphQLResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineOrmExtensionsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GraphQLResolverPass());
    }
}