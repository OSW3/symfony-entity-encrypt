<?php 
namespace OSW3\EntityEncrypt;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use OSW3\EntityEncrypt\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntityEncryptBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $projectDir = $container->getParameter('kernel.project_dir');
        
        (new Configuration)->generateProjectConfig($projectDir);
    }
}