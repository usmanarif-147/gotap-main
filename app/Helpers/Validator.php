<?php namespace app\Helpers;
use app\Models\DB;
use ReallySimpleJWT\Validate;

class Validator
{
    //public $validations;
    public function validate($input,$validations){
		$errors=[];
        foreach ($validations as $key => $rules) {
			
            foreach($rules as $validation)
            {
                $val=$input[$key] ?? null;
				
                $validation=strtolower($validation);
                if( ($validation=="req" || $validation=="required") && ( !$val || empty($val) ) )
                    $errors[]= $key." is required";
                else if( ( $validation=="email") && isset($input[$key]) && !filter_var($val, FILTER_VALIDATE_EMAIL) ) 
                    $errors[]= $key." is not valid email";
				
				//VALIDATING WHEATER NUMERIC OR NOT
                else if( $val && ($validation=="num" || $validation=="numeric") && !is_numeric ($val)  )
                    $errors[]= $key." is not a number";
					
				//VALIDATING WHEATER STRING OR NOT
				else if( $val && ($validation=="str" || $validation=="string" || $validation=="varchar") &&  !is_string($val)  ){
					if(is_int($val) || is_double($val) || is_numeric($val))
						$errors[]= $key." cannot be a numeric value";
					else if(is_array($val))
						$errors[]= $key." cannot be an array";
					else if(is_object($val))
						$errors[]= $key." cannot be an object";
					$errors[]= $key." is not a string/Varchar value";
				}
                    
					
				//CHECKS THE MIN LENGTH/VAL
				else if($val && strpos($validation,'min:')!==false){
					$len=str_replace("min:","",$validation);
					if(is_int($val) || is_double($val)){
						if($val<$len)
							$errors[]= $key." cannot be less than ".$len;
					}else{
						if(strlen($val)<$len)
							$errors[]= $key." length cannot be less than ".$len;
					}
				}
				
				//CHECKS THE MAX LENGTH/VAL
				else if( $val && strpos($validation,'max:')!==false){
					$len=str_replace("max:","",$validation);
					if(is_int($val) || is_double($val)){
						if($val>$len)
							$errors[]= $key." cannot be greater than ".$len;
					}else{
						if(strlen($val)>$len)
							$errors[]= $key." length cannot be greater than ".$len;
					}
				}

				//VALIDATING IF IN ARRAY
				else if( $val && strpos($validation,'in:')!==false){
					$arr=explode(',',str_replace("in:","",$validation));
					if(!in_array($val,$arr))
						$errors[]= "invalid ".$key." value ";
				}
				
				
				//VALIDATE CONFIRM PASSWORD
				else if( $val && strpos($validation,'confirm')!==false  ||  strpos($validation,'confirmed')!==false ){
					if(!isset($input[$key.'_confirmation'])){
						$errors[]= $key.'_confirmation is required';
					}
					else  if($input[$key]!=$input[$key.'_confirmation']){
						$errors[]= $key." and ".$key."_confirmation do not match";
					}
				}
				
				//CHECKS IF VALUE IS UNIQUE IN TABLE
				else if( $val && strpos($validation,'uniq:')!==false  ||  strpos($validation,'unique:')!==false ){
					if($val){
						$db=new DB();
						$table=str_replace("unique:","",$validation);
						$table=str_replace("uniq:","",$validation);
						if( $db->exist($table,$key,$val) )
						$errors[]= $key." is already taken";
					}
				}
				
				
            }
            
        }
		return $errors;
    }
	
	//EXAMPLE 
	/*
	$this->helper->validate([$inputArray,
		'email' => ['req','email','uniq:users'],  // EMAIL SHOULD BE required,proper email& should be unique in users table
		'name' => ['req','str','min:6','max:16'],      //name SHOULD BE required,varchar min length should be 6 and max 16
		'user_id' => ['num','min:0']  //user_id SHOULD BE numeric value and min value should be 0
	]);*/
}

?> 