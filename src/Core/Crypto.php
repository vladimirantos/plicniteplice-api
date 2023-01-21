<?php

namespace PlicniTeplice\Recipes\Api\Core;

use Defuse\Crypto\Key;

class Crypto
{
    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     */
    public static function encrypt(string $plaintext): string{
        $key = Key::loadFromAsciiSafeString($_ENV['ENCRYPTION_KEY']);
        return \Defuse\Crypto\Crypto::encrypt($plaintext, $key);
    }

    /**
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     */
    public static function decrypt(string $encryptedText): ?string{
        $key = Key::loadFromAsciiSafeString($_ENV['ENCRYPTION_KEY']);
        return \Defuse\Crypto\Crypto::decrypt($encryptedText, $key);
    }
}