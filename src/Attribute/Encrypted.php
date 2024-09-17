<?php 
namespace OSW3\EntityEncrypt\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Encrypted
{
    public function __construct(
        public string|null $salt=null
    ){}
}