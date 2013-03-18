<?php

namespace Smirik\ConfigBundle\Twig\Extension;

class ConfigExtension extends \Twig_Extension
{
    
    protected $config;
    protected $configs;
    
    public function getFilters()
    {
        return array(
            'config' => new \Twig_Filter_Method($this, 'config'),
        );
    }

    public function config($value, $default)
    {
        $res = $this->config->getValue($value);
        if ($res)
        {
            return $res;
        }
        return $default;
    }

    public function getName()
    {
        return 'smirik_config_extension';
    }
    
    public function setConfig($config)
    {
        $this->config = $config;
    }
    
}
