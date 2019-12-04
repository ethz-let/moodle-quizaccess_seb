<?php
namespace Tests\RNCryptor;

use PHPUnit\Framework\TestCase;
use RNCryptor\RNCryptor\Encryptor;

class EncryptorTest extends TestCase
{

    const SAMPLE_PLAINTEXT = 'Hello, how are you today?  I am doing fine.';
    const SAMPLE_PASSWORD = 'keep-out-123';

    public function testCanEncryptWithDefaultVersion()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD);
        $this->assertNotEmpty($encrypted);
    }

    public function testCanEncryptWithVersion0()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD, 0);
        $this->assertNotEmpty($encrypted);
    }
    
    public function testCanEncryptWithVersion1()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD, 1);
        $this->assertNotEmpty($encrypted);
    }
    
    public function testCanEncryptWithVersion2()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD, 2);
        $this->assertNotEmpty($encrypted);
    }

    public function testSelfEncryptedVersion0VectorIsVersion0()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD, 0);
        $actualVersion = ord(substr(base64_decode($encrypted), 0, 1));
        $this->assertEquals(0, $actualVersion);
    }
    
    public function testSelfEncryptedVersion1VectorIsVersion1()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD, 1);
        $actualVersion = ord(substr(base64_decode($encrypted), 0, 1));
        $this->assertEquals(1, $actualVersion);
    }

    public function testSelfEncryptedVersion2VectorIsVersion2()
    {
        $encryptor = new Encryptor;
        $encrypted = $encryptor->encrypt(self::SAMPLE_PLAINTEXT, self::SAMPLE_PASSWORD, 2);
        $actualVersion = ord(substr(base64_decode($encrypted), 0, 1));
        $this->assertEquals(2, $actualVersion);
    }
}
