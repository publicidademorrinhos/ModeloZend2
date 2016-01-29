<?php

namespace Base\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

use Zend\Cache\Storage\Adapter\Filesystem as CacheFilesystem;

/**
 * class Cache
 * Respnsavel por cachear o sistema.
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package Base\Plugin
 */
class Cache extends AbstractPlugin
{

    public $cache;

    public function __construct(CacheFilesystem $cache)
    {
        if (!is_null($cache)) {
            $this->cache = $cache;
        }
    }

    public function __invoke()
    {
        return $this->cache;
    }

}