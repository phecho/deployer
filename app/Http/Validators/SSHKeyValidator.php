<?php

namespace REBELinBLUE\Deployer\Http\Validators;

/**
 * Class for validating SSH private keys.
 */
class SSHKeyValidator
{
    /**
     * Validate that the SSH key looks valid.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @param  mixed  $parameters
     * @return bool
     */
    public function validate($attribute, $value, $parameters)
    {
        $value = trim($value);

        // Check for start marker for SSH key
        if (!preg_match('/^-----BEGIN (.*) PRIVATE KEY-----/i', $value)) {
            return false;
        }

        // Check for end marker for SSH key
        if (!preg_match('/-----END (.*) PRIVATE KEY-----$/i', $value)) {
            return false;
        }

        // Make sure key does not have passphrase
        if (preg_match('/ENCRYPTED/i', $value)) {
            return false;
        }

        return true;
    }
}
