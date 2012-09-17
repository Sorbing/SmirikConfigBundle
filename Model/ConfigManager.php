<?php
// 
//  ConfigManager.php
//  turprofi
//  
//  Created by Eddie Fisher on 2012-09-17.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
// 

namespace Smirik\ConfigBundle\Model;

use Smirik\ConfigBundle\Model\Config;
use Smirik\ConfigBundle\Model\ConfigQuery;

class ConfigManager
{
  public function get($key)
  {
    $keys = explode(".", $key);
    $stepOne = false;
    foreach ($keys as $value) {
      if (!$stepOne) {
        if (!$keyReturn = $this->getChildren($value)) {
          return false;
        } else {
          $stepOne = true;
        }
      } else {
        if (!$keyReturn = $this->getChildren($value, $keyReturn->getId())) {
          return false;
        }
      }
    }
    return $keyReturn;
  }
  
  private function getChildren($value, $id = null)
  {
    $keyReturn = ConfigQuery::create()
      ->filterByKey($value)
      ->filterByPid($id)
      ->findOne();
    if ($keyReturn) {
      return $keyReturn;
    } else {
      return false;
    }
  }
}