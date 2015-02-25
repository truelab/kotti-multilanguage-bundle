<?php

namespace Truelab\KottiMultilanguageBundle\Util;
use Truelab\KottiModelBundle\Model\ContentInterface;
use Truelab\KottiModelBundle\Model\Node;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class LanguageIndependentFields
 * @package Truelab\KottiMultilanguageBundle\Util
 */
class LanguageIndependentFields
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $this->sanitizeConfig($config);
    }

    public function sanitizeConfig($config)
    {
        $sanitized = [];

        foreach($config as $type => $typeFields) {
           if(is_array($typeFields) && !empty($typeFields)) {
               $sanitized[$type] = $typeFields;
           }
        }
        return $sanitized;
    }

    public function hasFields(NodeInterface $content)
    {
        return isset($this->config[$this->getKey($content)]);
    }

    public function getFields(NodeInterface $content)
    {
        if($this->hasFields($content)) {
            return $this->config[$this->getKey($content)];
        }else{
            return [];
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function getKey(NodeInterface $content)
    {
        return $content->getType();
    }
}
