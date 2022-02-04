<?php
class Env
{
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
     public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) 
        {
            return Env::value($default);
        }

        switch (strtolower($value)) 
        {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') 
        {
            return substr($value, 1, -1);
        }
        return $value;
    }
}
