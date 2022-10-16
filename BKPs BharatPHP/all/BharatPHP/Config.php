<?php

namespace BharatPHP;

class Config
{

    protected $found = false;
    protected $currentitem = array();
    protected static $config = array();

    public static function init($config = array())
    {
        foreach ($config as $key => $value) {
            self::$config[$key] = $value;
        }
    }

    public static function set($data)
    {
        self::$config = array_merge(self::$config, $data);
    }

    public static function __callStatic($method, $params = null)
    {
        try {
            $methodPrefix = substr($method, 0, 3);
            $methodName = substr($method, 3);

            // This part will set properties using set
            if ($methodPrefix == 'set') {
                if (count($params) < 2) {
                    $message = "Invalid parameter(s) given, method <strong>$method</strong> requires 2";
                    $message .= " parameter(s),  but " . count($params) . " given!";
                    throw new \Exception($message);
                }

                $key = strtolower($methodName) . '.' . $params[0];
                $value = $params[1];
                self::array_set(self::$config, $key, $value);
                return true;
            }

            // This part will return properties using get
            elseif ($methodPrefix == 'get') {

                // Set default value to return when no properties found
                $default = count($params) === 2 && !is_callable($params[1]) ? $params[1] : (count($params) === 2 && $params[0] === '' ? strtolower($methodName) : null);

                if ($params == null) {
                    $key = strtolower($methodName);
                    if ($key === 'all')
                        $result = self::$config;
                    else
                        $result = self::array_get(self::$config, $key);
                } else {
                    $key = strtolower($methodName);
                    $key .= isset($params[0]) ? '.' . $params[0] : '';

                    // If a closure is given in 2nd argumet with getMethod(), call it
                    $result = self::array_get(self::$config, $key, $default);
                    if (isset($params[1]) && is_callable($params[1])) {
                        return $params[1]($result);
                    }
                }
                return $result;
            } else {
                throw new \Exception("Undefined method <strong>$method</strong> has been called!");
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }


    public static function get($path, $default = '')
    {

        // We split each word seperated by a dot character
        $paths = explode('.', $path);

        $result = [];
        foreach ($paths as $path) {
            if (empty($result)) {
                $result = self::$config[$path];
            } else {

                $result = $result[$path];
            }
        }
        return $result;
    }
    public static function getAll()
    {

        return self::$config;
    }
    private static function array_get($array, $key, $default = null)
    {
        if (is_null($key))
            return $array;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) or !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment] ?: $default;
        }
        return $array;
    }

    private static function array_set(&$array, $key, $value)
    {
        if (is_null($key))
            return $array = $value;

        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) or !is_array($array[$key])) {
                $array[$key] = array();
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
    }

    public static function find($item = null, $array = null)
    {
        if (is_null($item))
            return null;
        //self::found = false;

        if (strpos($item, '.')) {
            $arr = explode('.', $item);
            if (count($arr) > 2) {
                $itemToSearch = join('.', array_slice($arr, 1));
            } else {
                $itemToSearch = $arr[1];
            }
            return self::findItemIn($itemToSearch, $arr[0]);
        } else {
            $array = !is_null($array) ? $array : self::$config;
            foreach ($array as $key => $value) {
                if ($key === $item) {
                    self::$currentitem = $value;
                    self::$found = true;
                    break;
                } else {
                    if (is_array($value)) {
                        self::find($item, $value);
                    }
                }
            }
        }

        if (!self::$found) {
            return false;
        }
        return self::$currentitem;
    }

    private static function findItemIn($item, $key)
    {
        $array = self::find($key);
        if ($array)
            return self::array_get($array, $item);
        return false;
    }

    public static function isExist($key = null)
    {
        return $key = is_null($key) ? false : (self::find($key) ? true : false);
    }
}
