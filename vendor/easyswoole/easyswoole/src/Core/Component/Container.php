<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/12/4
 * Time: 下午2:46
 */

namespace EasySwoole\Core\Component;


class Container
{
    private $container = [];
    private $allowKeys = null;

    function __construct(array $allowKeys = null)
    {
        $this->allowKeys = $allowKeys;
    }

    function set($key, $item)
    {
        if(is_array($this->allowKeys) && !in_array($key,$this->allowKeys)){
            return false;
        }
        $this->container[$key] = $item;
        return $this;
    }

    function delete($key)
    {
        if(isset($this->container[$key])){
            unset($this->container[$key]);
        }
        return $this;
    }

    function get($key)
    {
        if(isset($this->container[$key])){
            return $this->container[$key];
        }else{
            return null;
        }
    }

    function all():array
    {
        return $this->container;
    }

}