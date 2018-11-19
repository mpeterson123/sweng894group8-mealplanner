<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Represents a user's household
 */
class Household {
    private
        $id,
        $name,
        $owner;

    /**
     * Set household id
     * @param integer  $id Household id
     */
    public function setId($id):void
    {
        if(!$id)
        {
            throw new \Exception("Id cannot be empty", 1);
        }

        $id = intval($id);
        if($id < 1){
            throw new \Exception("Id must be greater than 0", 1);
        }

        $this->id = $id;
    }

    /**
     * Get household id
     * @return [type] [description]
     */
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

    public function getOwner(){
      return $this->owner;
    }

    public function setOwner($owner){
		$owner = trim($owner);

		if($owner == ''){
			throw new \Exception("Owner cannot be empty", 1);
		}

		if(strlen($owner) > 32){
			throw new \Exception("Owner cannot be longer than 32 characters", 1);
		}

		if(!preg_match('/^[a-z0-9]+$/i', $owner)){
			throw new \Exception("Owner cannot be longer than 32 characters", 1);
		}
	    $this->owner = $owner;
    }

    public function genInviteCode(){
      // start with id
      $code = $this->id;
      // add 10 million (to ensure consistant length)
      $code += 10000000;
      // add checksum (sum of digits mod 10)
      $sum = array_sum(str_split($code));
      $sum %= 10;
      if($sum == 0)   $sum = 10;
      $code = $sum.''.$code;
      // add checkmult (product of non-zero digits mod 10)
      $mult = array_product(array_filter(str_split($code)));
      $mult %= 10;
      $code = $code.''.$mult;
      // use base36 encoding to shorten length
      return strtoupper(base_convert($code,10,36));
    }

    public function reverseCode($code){
      $code = strtolower($code);
      $code = base_convert($code,36,10);
      $spSum = substr($code, 0, 2);
      $code = substr($code, 0, strlen($code)-1);  // Remove multcheck
      if($spSum == 10)
        return substr($code, 2)-10000000;
      return substr($code, 1)-10000000;
    }
}
