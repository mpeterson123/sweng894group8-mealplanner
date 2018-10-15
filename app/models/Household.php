<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class Household {
    private
        $id,
        $name;

    public function setId($id)
    {
        if(!$id)
        {
            throw new \Exception("Id cannot be empty", 1);
        }

        if(gettype($id) !== 'integer'){
            throw new \Exception("Id must be an integer", 1);
        }

        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set household name
     * @param string $name Household name
     */
    public function setName($name):void{
        if($name == ''){
            throw new \Exception(
                "Household name cannot be empty", 1);
        }

        if(strlen($name) > 50){
            throw new \Exception(
                "Household name cannot be longer than 50 characters", 1);
        }

        $this->name = trim($name);
    }

    /**
     * Get household name
     * @return string Household name
     */
    public function getName():string{
        return $this->name;
    }

    public function genInviteCode(){
      // start with id
      $code = $this->id;
      // add 10 million (to ensure consistant length)
      $code += 10000000;
      // add checksum (sum of digits mod 10)
      $sum = array_sum(str_split($code));
      $sum %= 10;
      $code = $sum.''.$code;
      // add checkmult (product of non-zero digits mod 10)
      $mult = array_product(array_filter(str_split($code)));
      $mult %= 10;
      $code = $code.''.$mult;
      // use base36 encoding to shorten length
      return strtoupper(base_convert($code,10,36));
    }
}
