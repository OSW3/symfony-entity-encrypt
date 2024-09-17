<?php

use OSW3\EntityEncrypt\Enum\Algo;

return static function($definition)
{
    $definition->rootNode()->children()

        ->enumNode('algo')
            ->info('Specifies the encryption algo.')
            ->values(Algo::toArray())
            ->defaultValue(Algo::AES256->value)
        ->end()

    ->end();
};