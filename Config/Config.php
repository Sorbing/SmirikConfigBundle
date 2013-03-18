<?php

namespace Smirik\ConfigBundle\Config;

use Smirik\ConfigBundle\Model\ConfigQuery;
use Smirik\ConfigBundle\Model\Config as ModelConfig;

class Config
{

    /**
     * Get Config related to complex key
     * @param  string $key
     * @return Smirik\ConfigBundle\Model\Config|false
     */
    public function get($key)
    {
        $keys    = explode(".", $key);
        $stepOne = false;
        foreach ($keys as $key) {
            if (!$stepOne) {
                if (!$keyReturn = $this->getByKeyAndPid($key)) {
                    return false;
                } else {
                    $stepOne = true;
                }
            } else {
                if (!$keyReturn = $this->getByKeyAndPid($key, $keyReturn->getId())) {
                    return false;
                }
            }
        }

        return $keyReturn;
    }

    /**
     * Get all configs in the app
     * @return PropelObjectCollection
     */
    public function getAll()
    {
        return ConfigQuery::create()
            ->find()
        ;
    }
    
    /**
     * Get raw value of config instead of Config object
     * @param string $key
     * @return mixed value
     */
    public function getValue($key)
    {
        $obj = $this->get($key);
        if (!$obj) {
            return false;
        }

        return $obj->getValue();
    }

    /**
     * Get raw value of config. If not exists -> creates it
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    public function getValueOrCreate($key, $value = null, $type = null)
    {
        $obj = $this->get($key);
        if (!$obj) {
            $obj = $this->set($key, $value, $type, true);

            return $obj->getValue();
        }

        return $obj->getValue();
    }

    /**
     * Get Config related to key & pid
     * @param  string                                 $key
     * @param  integer|null                           $id
     * @return Smirik\ConfigBundle\Model\Config|false
     */
    private function getByKeyAndPid($key, $id = null)
    {
        $keyReturn = ConfigQuery::create()
            ->filterByKey($key)
            ->filterByPid($id)
            ->findOne();
        if ($keyReturn) {
            return $keyReturn;
        }

        return false;
    }

    /**
     * Set config value (or override)
     * @param  string                           $key
     * @param  string                           $value
     * @param  boolean                          $create_if_not_exists
     * @return Smirik\ConfigBundle\Model\Config
     */
    public function set($key, $value, $type = 'text', $create_if_not_exists = true)
    {
        $keys = explode('.', $key);

        $first = ConfigQuery::create()
            ->filterByKey($keys[0])
            ->filterByPid(null)
            ->findOne();

        if (!$first) {
            if (!$create_if_not_exists) {
                return false;
            }
            $first = new ModelConfig();
            $first->setKey($keys[0]);
            if (count($keys) == 1) {
                $first->setType($type);
                $first->setValue($value);
            } else {
                $first->setValue('');
            }
            $first->setPid(null);
            $first->save();
        }

        unset($keys[0]);

        foreach ($keys as $one) {
            $new = ConfigQuery::create()
                ->filterByKey($one)
                ->filterByPid($first->getId())
                ->findOne();

            if (!$new) {
                if (!$create_if_not_exists) {
                    return false;
                }
                $new = new ModelConfig();
                $new->setKey($one);
                $new->setValue('');
                $new->setPid($first->getId());
                $new->save();
            }

            $first = $new;
        }

        $first->setType($type);
        $first->setValue($value);
        $first->save();

        return $first;
    }

}
