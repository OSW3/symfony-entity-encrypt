<?php 
namespace OSW3\EntityEncrypt\EventListener;

use ReflectionClass;
use OSW3\EntityEncrypt\Attribute\Encrypted;
use OSW3\EntityEncrypt\Service\EncryptionService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use OSW3\EntityEncrypt\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EncryptionListener
{    
    public function __construct(
        #[Autowire(service: 'service_container')] private ContainerInterface $container,
        private EncryptionService $encryptionService
    )
    {
        $config = $container->getParameter(Configuration::NAME);
        $this->encryptionService->setAlgo($config['algo']);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->encryptProperties($args->getObject());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->encryptProperties($args->getObject());
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $this->decryptProperties($args->getObject());
    }


    private function encryptProperties($entity): void
    {
        $reflectionClass = new ReflectionClass($entity);
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(Encrypted::class);
            if (!empty($attributes)) {
                $attributeInstance = $attributes[0]->newInstance();

                $property->setAccessible(true);
                $value = $property->getValue($entity);

                if ($value !== null) {
                    $encryptedValue = $this->encryptionService->encrypt($value, $attributeInstance->salt);
                    $property->setValue($entity, $encryptedValue);
                }
            }
        }
    }

    private function decryptProperties($entity): void
    {
        $reflectionClass = new ReflectionClass($entity);
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(Encrypted::class);
            if (!empty($attributes)) {
                $attributeInstance = $attributes[0]->newInstance();

                $property->setAccessible(true);
                $value = $property->getValue($entity);

                if ($value !== null) {
                    $decryptedValue = $this->encryptionService->decrypt($value, $attributeInstance->salt);
                    $property->setValue($entity, $decryptedValue);
                }
            }
        }
    }
}