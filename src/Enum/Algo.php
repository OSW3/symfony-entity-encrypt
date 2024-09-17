<?php 
namespace OSW3\EntityEncrypt\Enum;

use OSW3\EntityEncrypt\Trait\EnumTrait;

enum Algo: string 
{
    use EnumTrait;

    case BASE64 = 'base64';
    case AES128 = 'aes128';
    case AES192 = 'aes192';
    case AES256 = 'aes256';
}