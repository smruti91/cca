<?php

 function crypto_rand($min,$max,$pedantic=True) {
	    $diff = $max - $min;
	    if ($diff <= 0) return $min; // not so random...
	    $range = $diff + 1; // because $max is inclusive
	    $bits = ceil(log(($range),2));
	    $bytes = ceil($bits/8.0);
	    $bits_max = 1 << $bits;
	   
	    $num = 0;
	    do {
	        $num = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes))) % $bits_max;
	        if ($num >= $range) {
	            if ($pedantic) continue; 
	            
	            $num = $num % $range;  
	        }
	        break;
	    } while (True);  
	    return $num + $min;
	}


	function generateSQL($sql, $params, $close, &$db)
		{

			
			// ==========Code to detect the parameter type and push it to the parameter array===========
			if ($params != null){
		        $types = '';
		        foreach ($params as $param)
		        {
		            if (is_int($param)) {
		                $types .= 'i';
		            } elseif (is_float($param)) {
		                $types .='d';
		            } elseif (is_string($param)){
		                $types .='s';
		            } else {
		                $types .='b';
		            }
		        }
		    }
		    array_unshift($params , $types);
		  $result = new \stdClass();
		   // $results = new ArrayObject();
		  //========================================================================================

          //$stmt = $db->prepare($sql) or die ("Failed to prepared the statement!");
          //call_user_func_array(array($stmt, 'bind_param'), refValues($params));
		    try {
		      $stmt = $db->prepare(htmlentities($sql)); 
		      //print_r($stmt);
		      if ( false===$stmt ) {
		      	throw new Exception('prepare() failed: ' . htmlspecialchars($db->error));
				}else{
					$refArr = refValues($params); 
					$ref    = new ReflectionClass('mysqli_stmt'); 
					$method = $ref->getMethod("bind_param"); 
					$rc = $method->invokeArgs($stmt,$refArr);
					$rc=$stmt->execute();
		           if($close)
					   {
							$result->affected_rows = $stmt->affected_rows; 
							$result->insert_id = $stmt->insert_id; 
					   } 
					   else 
							{
							   $meta = $stmt->result_metadata();
							   while ( $field = $meta->fetch_field() ) 
								   {
									   $parameters[] = &$row[$field->name];
								   }
								$meta->close(); 
			            		$method = new ReflectionMethod('mysqli_stmt', 'bind_result'); 
			            		$method->invokeArgs($stmt, refValues($parameters));
			            		$results =  array();
								while ( $stmt->fetch() ) 
									{  
									   $x = array();  
									   foreach( $row as $key => $val ) 
										   {  
											  $x[$key] = $val;  
										   }  
									   $results[] = $x;  
									}
								$result = $results;
							}
			   			$stmt->close();
			   		}
			   	}catch (Exception $e) {
			   		errorlog($e->getMessage());
				}
	           return  $result;
	           
		}
		 function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

    // ---------------Function to fetch team id from userid--------------

    function find_teamid($userid, &$db){
    	$resultteam= mysqli_query($db,"select team_id from `cca_team_emp_role` where emp_id='".$userid."'");
        $team_idre=mysqli_fetch_row($resultteam);
       	 $team_id=$team_idre[0];
       	return $team_id;
    }

    function find_role($rolid,&$db){
    	$resultrole= mysqli_query($db,"select F_descr from `cca_roles` where ID='".$rolid."'");
    	 $rolerow=mysqli_fetch_row($resultrole);
    	 $role=$rolerow[0];
    	 return $role;
    }

    function find_teamname($teamid,&$db){
    	$resultrole= mysqli_query($db,"select team_name from `cca_team` where id='".$teamid."'");
    	 $rolerow=mysqli_fetch_row($resultrole);
    	 $role=$rolerow[0];
    	 return $role;
    }

    function find_planname($plan,&$db){
    	$resultplan= mysqli_query($db,"select plan_name from `cca_plan` where id='".$plan."'");
    	 $planrow=mysqli_fetch_row($resultplan);
    	 $plan=$planrow[0];
    	 return $plan;
    }
	 function find_institutionname($inst_id,&$db){
    	$resultplan= mysqli_query($db,"select institution_name from `cca_institutions` where id='".$inst_id."'");
    	 $planrow=mysqli_fetch_row($resultplan);
    	 $plan=$planrow[0];
    	 return $plan;
    }

     function find_username($userid,&$db){
    	$resultuser= mysqli_query($db,"select Name from `cca_users` where ID='".$userid."'");
    	 $userrow=mysqli_fetch_row($resultuser);
    	 $name=$userrow[0];
    	 return $name;
    }
    function findpara_head($paraid,&$db){

    	$resultuser= mysqli_query($db,"select para_head from `cca_paragraph` where id='".$paraid."'");
    	 $userrow=mysqli_fetch_row($resultuser);
    	 $name=$userrow[0];
    	 return $name;
    }
    // Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

	?>