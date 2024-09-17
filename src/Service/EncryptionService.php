<?php 
namespace OSW3\EntityEncrypt\Service;

use OSW3\EntityEncrypt\DependencyInjection\Configuration;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EncryptionService
{
    public function __construct(
        // private ?string $key=null, 
        private string $algo="base64",
    ){}

    public function setAlgo(string $algo): static 
    {
        $this->algo = $algo;

        return $this;
    }

    public function encrypt(string $data, ?string $key=null): string
    {
        return match($this->algo) {
            'base64' => base64_encode($data),
            'aes128' => $this->aes128_encrypt($data, $key), // OpenSSL AES 128
            'aes192' => $this->aes192_encrypt($data, $key), // OpenSSL AES 192
            'aes256' => $this->aes256_encrypt($data, $key), // OpenSSL AES 256
            default  => $this->aes256_encrypt($data, $key)
        };
    }

    public function decrypt(string $encryptedData, ?string $key=null): string
    {
        return match($this->algo) {
            'base64' => base64_decode($encryptedData),
            'aes128' => $this->aes128_decrypt($encryptedData, $key), // OpenSSL AES 128
            'aes192' => $this->aes192_decrypt($encryptedData, $key), // OpenSSL AES 192
            'aes256' => $this->aes256_decrypt($encryptedData, $key), // OpenSSL AES 256
            default  => $this->aes256_decrypt($encryptedData, $key)
        };
    }

    // AES-128 openssl
    private function aes128_encrypt(string $data, ?string $key=null): string 
    {
        return $this->openssl_aes_encrypt('aes-128-cbc', $data, $key);
    }
    private function aes128_decrypt(string $encryptedData, ?string $key=null): string 
    {
        return $this->openssl_aes_decrypt('aes-128-cbc', $encryptedData, $key);
    }

    // AES-192 openssl
    private function aes192_encrypt(string $data, ?string $key=null): string 
    {
        return $this->openssl_aes_encrypt('aes-192-cbc', $data, $key);
    }
    private function aes192_decrypt(string $encryptedData, ?string $key=null): string 
    {
        return $this->openssl_aes_decrypt('aes-192-cbc', $encryptedData, $key);
    }

    // AES-256 openssl
    private function aes256_encrypt(string $data, ?string $key=null): string 
    {
        return $this->openssl_aes_encrypt('aes-256-cbc', $data, $key);
    }
    private function aes256_decrypt(string $encryptedData, ?string $key=null): string 
    {
        return $this->openssl_aes_decrypt('aes-256-cbc', $encryptedData, $key);
    }

    private function openssl_aes_encrypt(string $cipher, string $data, ?string $key=null): string 
    {
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    private function openssl_aes_decrypt(string $cipher, string $encryptedData, ?string $key=null): string 
    {
        $data = base64_decode($encryptedData);
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
}