<?php

namespace Smirik\ConfigBundle\Model;

use Smirik\ConfigBundle\Model\om\BaseConfig;

class Config extends BaseConfig
{
  
  public function setValue($value)
  {
    if (in_array($this->getType(), array('array')))
    {
      return parent::setValue(json_encode($value));
    }
    return parent::setValue($value);
  }
  
  public function getValue()
  {
    $value = parent::getValue();
    if (in_array($this->getType(), array('array')))
    {
      return (array)json_decode($value);
    }
    return $value;
  }
  
  
}
