<?php

namespace src\lib;


class RRedis 
{

    const TIME_SHORT = 60;
    const TIME_MEDIUM = 120;
    const TIME_LONG = 300;

    /**
     *
     * @var Boolean
     */
    private $connStatus = false;

    /**
     *
     * @param String $db
     * @return boolean
     */
    public function __construct($db = 0)
    {
        
//        if (class_exists('Redis')) {
//            try {
//                $this->connect(REDIS_CONNECTION);
//                if (defined('REDIS_AUTH')) {
//                    $this->auth(REDIS_AUTH);
//                }
//                $this->select($db === 0 ? REDIS_DB : $db);
//                $this->connStatus = true;
//            } catch (\RedisException $e) {
//                $this->connStatus = false;
//            }
//        }
    }

    /**
     *
     * @param String $method
     * @param Mixed $arguments
     * @return Function Call
     */
    public function __call($method, $arguments)
    {
        return false;
    }

    /**
     *
     * @param String $key
     * @param Mixed | Array | String $val
     * @param Integer $timeout in Seconds
     * @return type
     */
    public function set($key, $val, $timeout = NULL, $opt = NULL)
    {
        return false;
    }

    /**
     *
     * @param String $key
     * @return Mixed | Array | String
     */
    public function get($key)
    {
        return false;
    }

    /**
     *
     * @param String $key
     * @return Boolean
     */
    public function exists($key, ...$other_keys)
    {

        return false;
    }

    /**
     *
     * @param String $key
     * @return Boolean
     */
    public function del($key, ...$other_keys)
    {

        return false;
    }

    /**
     * Close the connection when class closure
     */
    function __destruct()
    {
        $this->close();
    }
}
