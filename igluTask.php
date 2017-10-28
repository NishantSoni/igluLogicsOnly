<?php
    
	include './helpers/Database.php';
	include './helpers/Rest.inc.php';
	
	class Iglutask {

		public function verifyItems(){

			// we can have 2 cases : 
			// 1. Alex changes the prices of the items.
			// 2. Alex does not change the price of the items. 
			// let say, books of diffent technologies. this time it is static but later it will be dynamic
			$origItems 	= ['php' , 'laravel' , 'symfony' , 'yii' , 'cakephp'];
			$origPrices = [100 , 200 , 300 , 400 , 500];
			$items 		= ['laravel' , 'yii' , 'cakephp'];
			$prices 	= [200 , 4000 , 5000];

			// first we will combine the arrays of original_items and original_price and same for another two arrays
			$origInfo = array_combine($origItems , $origPrices);
			$alexInfo = array_combine($items , $prices);

			// here array_intersect_assoc returns the matched items. so we will subsctract it from the count of duplicate array. this will be result as expected...
			echo $incorrectRecord = count($alexInfo) - count(array_intersect_assoc($origInfo, $alexInfo)); 

		}

		public function searchTicket(){


			// Check HTTP Request, it should be GET only
			if($this->get_request_method() != "GET")
			{
				$error = array('status' => "failed", "msg" => "Not allowed" , "data"=>"");
				$this->response(json_encode($error),406);
			}

			// Create the object of helpers
			$res = new REST();
			$db = new Database();
			
			// first we will check 'title' string is exist in the request or not
			if( !empty($this->checkExist($_GET['title'])) ){

				// if page number exists then take it, otherwise set it to 1..
				if( !empty($this->checkExist($_GET['title'])) ){
					$page = $_GET['page'];
				}else{
					$page = 1;
				}

				// fetch results from the database
				$resultArr = $db->getSearchResult('table_name' , $title , $page);

				// check the records exist in the DB or not
				if( count($resultArr) > 0 ){
					$responseArr = array(
									"status" 		=> "success", 
									"msg" 			=> "tickets are fetched successfully.." ,
									"page" 			=> $page,
									"per_page" 		=> 10,
									"total"		  	=> count($resultArr),
									"total_pages" 	=> ceil(count($resultArr)/$results_per_page),
									"data"			=> $resultArr
								);
					$status_code = 200;
				}else{

					$responseArr = array(
									"status" 		=> "success", 
									"msg" 			=> "tickets are not matched with the title" ,
									"page" 			=> "",
									"per_page" 		=> "",
									"total"		  	=> 0,
									"total_pages" 	=> 0,
									"data"			=> ""
								);
					$status_code = 200;
				}

			}else{
				
				// return error 
				$responseArr = array(
							"status" 		=> "failed", 
							"msg" 			=> "All the fields are required" ,
							"page" 			=> 0,
							"per_page" 		=> 0,
							"total"		  	=> 0,
							"total_pages" 	=> 0,
							"data"			=> ""
						);
				$status_code = 400;
			}

			// finally return response..
			$res->response(json_encode($responseArr), $status_code); 
		}

		function checkExist(&$value, $default = null)
		{
		    return isset($value) ? $value : $default;
		}

	}

	$start = new IGLUTASK;
	$start->verifyItems();

?>