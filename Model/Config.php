<?php

namespace Smirik\ConfigBundle\Model;

use Smirik\ConfigBundle\Model\om\BaseConfig;

class Config extends BaseConfig
{

    /**
     * saves value & encodes complex values into json respresentation
     * @param $value
     * @return mixed
     */
    public function setValue($value)
    {
        if (in_array($this->getType(), array('array'))) {
            return parent::setValue(json_encode($value));
        }
        return parent::setValue($value);
    }

    /**
     * Returns value of complex value if saved config is array
     * @return mixed
     */
    public function getValue()
    {
        $value = parent::getValue();
        if (in_array($this->getType(), array('array'))) {
            $arr     = json_decode($value);
            $new_arr = array();
            foreach ($arr as $key => $value) {
                $new_arr[(int)$key] = $value;
            }
            return $new_arr;
        }
        return $value;
    }


}
