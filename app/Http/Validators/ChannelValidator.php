<?php

namespace REBELinBLUE\Deployer\Http\Validators;

/**
 * Class for validating slack channels.
 */
class ChannelValidator
{
    /**
     * Validate the the channel name is valid for slack, i.e. starts with # or @.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @param  mixed  $parameters
     * @return bool
     */
    public function validate($attribute, $value, $parameters)
    {
        $first_character = substr($value, 0, 1);

        return (($first_character === '#' || $first_character === '@') && strlen($value) > 1);
    }
}
