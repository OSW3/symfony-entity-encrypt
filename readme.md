# Symfony EntityEncrypt

Encrypt & Decrypt entity data in database

## How to install

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require osw3/symfony-entity-encrypt
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php 
// config/bundles.php

return [
    // ...
    OSW3\EntityEncrypt\EntityEncryptBundle::class => ['all' => true],
];
```

## How to use

### Step 1: Edit the configuration

Edit the configuration file `config/packages/entity_encrypt.yaml`

```yaml 
entity_encrypt:
    algo: aes256
```

### Step 2: Add `Encrypted` attribute to your entities

```php 
use OSW3\EntityEncrypt\Attribute\Encrypted;

class Message
{
    // ...

    #[ORM\Column(type: Types::TEXT)]
    #[Encrypted] // Just add this Attribute
    private ?string $secret = null;

    // ...
}
```
