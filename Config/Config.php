<?php

namespace Smirik\ConfigBundle\Config;

use Smirik\ConfigBundle\Model\ConfigQuery;

class Config
{
	
	/**
	 * Get Config related to comp. key
	 * @param string $key
	 * @return Smirik\ConfigBundle\Model\Config|false
	 */
  public function get($key)
  {
    $keys = explode(".", $key);
    $stepOne = false;
    foreach ($keys as $key) 
		{
      if (!$stepOne) 
			{
        if (!$keyReturn = $this->getByKeyAndPid($key)) 
				{
          return false;
        } else 
				{
          $stepOne = true;
        }
      } else 
			{
        if (!$keyReturn = $this->getByKeyAndPid($key, $keyReturn->getId())) 
				{
          return false;
        }
      }
    }
    return $keyReturn;
  }
  
	/**
	 * Get Config related to key & pid
	 * @param string $key
	 * @param integer|null $id
	 * @return Smirik\ConfigBundle\Model\Config|false
	 */
  private function getByKeyAndPid($key, $id = null)
  {
		$keyReturn = ConfigQuery::create()	
		  ->filterByKey($key)
		  ->filterByPid($id)
		  ->findOne();
		if ($keyReturn) 
		{
		  return $keyReturn;
		}
		return false;
  }
}