<?php 

	require_once 'DbConnect.php';
	require_once 'Release.php';
	
	$response = array();
	
	if(isset($_GET['apicall'])){
		
		switch($_GET['apicall']){
			
			case 'signup':
				if(isTheseParametersAvailable(array('username','email','password'/*,'gender'*/))){
					$username = $_POST['username']; 
					$email = $_POST['email']; 
					$password = md5($_POST['password']);
					
					$query="SELECT ". USER_ID ." FROM ". USERS_TABLE ." WHERE ". USER_NAME ."= ? OR ". USER_EMAIL ."= ?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("ss", $username, $email);
					$stmt->execute();
					$stmt->store_result();
					
					if($stmt->num_rows > 0){
						$response['error'] = true;
						$response['message'] = 'User already registered';
					}else{
						$stmt->close();
						$query="INSERT INTO ". USERS_TABLE ." (". USER_NAME .", ". USER_EMAIL .", ". USER_PASSWORD .") VALUES (?, ?, ?)";
						$stmt = $conn->prepare($query);
						$stmt->bind_param("sss", $username, $email, $password);

						if($stmt->execute() && $stmt->affected_rows == 1){
							$id = $stmt->insert_id;
							
							$user = array(
								'id'=>$id, 
								'username'=>$username, 
								'email'=>$email,
							);
							$response['error'] = false; 
							$response['message'] = 'User registered successfully'; 
							$response['user'] = $user; 
						}
						else {
							$response['error'] = true; 
							$response['message'] = 'Insert query isnt executed properly'; 
						}
					}
					$stmt->close(); // zamknięcie połączenia dla wszystkich if i else, 
									// zamiast dla każdego przypadku osobno
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
				
			break; 
			
			case 'login':
				if(isTheseParametersAvailable(array('email', 'password'))){
					$email = $_POST['email']; /*'username'*/
					$password = md5($_POST['password']); 
					
					$query="SELECT ". USER_ID .", ". USER_NAME .", ". USER_EMAIL .
						" FROM ". USERS_TABLE .
						" WHERE ". USER_EMAIL . "= ? AND ". USER_PASSWORD ."= ?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("ss",$email , $password);
					
					$stmt->execute();
					$stmt->store_result();
					if($stmt->num_rows > 0){
						$stmt->bind_result($id, $username, $email);
						$stmt->fetch();
						
						$user = array(
							'id'=>$id, 
							'username'=>$username, 
							'email'=>$email,
						);
						$response['error'] = false; 
						$response['message'] = 'Login successfull'; 
						$response['user'] = $user; 
					}else{
						$response['error'] = false; 
						$response['message'] = 'Invalid email or password';
					}
					$stmt->close();
				}
				else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break; 
			
			case 'add_quantity_product':
				if(isTheseParametersAvailable(array('id','quantity'))){
					$quantity = $_POST['quantity'];
					$id = $_POST['id'];
					
					$query="UPDATE ". PRODUCTS_TABLE ." SET ". PRODUCT_QUANTITY ." = ? WHERE ". PRODUCT_ID ." = ?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("ii", $quantity, $id);
					if($stmt->execute() && $stmt->affected_rows == 1) {
						$response['productId'] = $id; 
						$response['error'] = false; 
						$response['message'] = 'Product quantity updated successfully.'; 
					}
					else {
						$response['error'] = true; 
						$response['message'] = 'Product with given id doesnt exist.'; 
					}
					$stmt->close();
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;
			
			case 'get_product':
				if(isset($_GET['id'])){
					$id = $_GET['id'];
					$query="SELECT ". PRODUCT_ID .", ". PRODUCT_NAME .", ". PRODUCT_SYMBOL .", ". PRODUCT_QUANTITY .
						" FROM ". PRODUCTS_TABLE ." WHERE " . PRODUCT_ID ." = ?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("i", $id);
					$stmt->execute();
					$stmt->store_result();
					
					if($stmt->num_rows > 0){
						$stmt->bind_result($id, $productname, $productsymbol, $quantity);
						$stmt->fetch();
						$product = array(
							'id'=>$id,
							'quantity'=>$quantity, 
							'productname'=>$productname, 
							'productsymbol'=>$productsymbol
						);
						$response['product'] = $product; 
						$response['error'] = false; 
						$response['message'] = 'Product exist.'; 
					}
					else {
						$response['error'] = true; 
						$response['message'] = 'Product with given id doesnt exist.'; 
					}
					$stmt->close();
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;
			
			case 'get_all_products':
				if(strcmp($_SERVER['REQUEST_METHOD'], 'GET') == 0)
				{
					$query="SELECT * FROM ". PRODUCTS_TABLE;
					$stmt = $conn->prepare($query);
					$stmt->execute();
					
					$products = fetchObjects($stmt);
					
					$stmt->close();
					$response['error'] = false; 
					$response['message'] = 'Products fetched successfully'; 
					$response['object'] = $products; 
				}
				else {
					$response['error'] = true;
					$response['message'] = 'Only HTTP GET request method allowed.'; 
				}
			break;

			case 'add_product':
				if(isTheseParametersAvailable(array('quantity','productname','productsymbol'))){
					$productsymbol = $_POST['productsymbol']; 
					$productname = $_POST['productname']; 
					$quantity = $_POST['quantity'];

					$query="SELECT ". PRODUCT_SYMBOL ." FROM ". PRODUCTS_TABLE ." WHERE ". PRODUCT_SYMBOL ."= ?";
					$stmt = $conn->prepare($query); // OR email = ?");
					$stmt->bind_param("s", $productsymbol);
					$stmt->execute();
					$stmt->store_result();
					
					if($stmt->num_rows > 0){
						$response['error'] = true;
						$response['message'] = 'Product already registered';
						$stmt->close();
					}else{
						$stmt->close();
						$query="INSERT INTO ". PRODUCTS_TABLE .
							" (". PRODUCT_QUANTITY .", ". PRODUCT_NAME .", ". PRODUCT_SYMBOL .") VALUES (?, ?, ?)";
							
						$stmt = $conn->prepare($query);
						$stmt->bind_param("sss", $quantity, $productname, $productsymbol);

						if($stmt->execute() && $stmt->affected_rows == 1){
							$productid = $stmt->insert_id;
							$product = array(
								'id'=>$productid,
								'quantity'=>$quantity, 
								'productname'=>$productname, 
								'productsymbol'=>$productsymbol
							);
							$response['error'] = false; 
							$response['message'] = 'Product registered successfully'; 
							$response['product'] = $product; 
						}
						else {
							$response['error'] = true; 
							$response['message'] = 'Insert query isnt executed properly'; 
						}
						$stmt->close();
					}
					
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
				
			break; 

			case 'check_product':
				if(isTheseParametersAvailable(array('productsymbol'))){
					$productsymbol = $_POST['productsymbol'];
					
					$query="SELECT ". PRODUCT_ID .", ". PRODUCT_QUANTITY . ", ". PRODUCT_NAME .", ". PRODUCT_SYMBOL .
								" FROM ". PRODUCTS_TABLE ." WHERE ". PRODUCT_SYMBOL ." = ?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("s",$productsymbol);
					$stmt->execute();
					$stmt->store_result();
					if($stmt->num_rows > 0){
						$stmt->bind_result($id, $quantity, $productname, $productsymbol);
						$stmt->fetch();
						
						$product = array(
							'id'=> $id,
							'quantity'=>$quantity, 
							'productname'=>$productname, 
							'productsymbol'=>$productsymbol,
						);
						$response['error'] = false; 
						$response['message'] = 'This product is in our database'; 
						$response['product'] = $product; 
					}else{
						$response['error'] = false; 
						$response['message'] = 'This product is`nt in our database yet';
					}
					$stmt->close();
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;

			case 'check_employee':
				if(isTheseParametersAvailable(array('symbol'))){
					$symbol = $_POST['symbol'];
					
					$query="SELECT ". EMPLOYEE_ID .", ". EMPLOYEE_SURNAME . ", ". EMPLOYEE_NAME .", ". EMPLOYEE_SYMBOL .
								" FROM ". EMPLOYEES_TABLE ." WHERE ". EMPLOYEE_SYMBOL ." = ?";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("s",$symbol);
					$stmt->execute();
					$stmt->store_result();
					if($stmt->num_rows > 0){
						$stmt->bind_result($id, $surname, $name, $symbol);
						$stmt->fetch();
						
						$employee = array(
							'id'=> $id,
							'surname'=>$surname, 
							'name'=>$name, 
							'symbol'=>$symbol,
						);
						$response['error'] = false; 
						$response['message'] = 'This employee is in our database'; 
						$response['employee'] = $employee; 
					}else{
						$response['error'] = false; 
						$response['message'] = 'This employee is`nt in our database yet';
					}
					$stmt->close();
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;
			
			case 'get_all_employees':
				if(strcmp($_SERVER['REQUEST_METHOD'], 'GET') == 0)
				{
					$query="SELECT * FROM ". EMPLOYEES_TABLE ." ORDER BY ". EMPLOYEE_SURNAME ." ASC";
					$stmt = $conn->prepare($query);
					$stmt->execute();
					
					$employees = fetchObjects($stmt);
					
					$stmt->close();
					$response['error'] = false; 
					$response['message'] = 'Employees fetched successfully'; 
					$response['object'] = $employees; 
				}
				else {
					$response['error'] = true;
					$response['message'] = 'Only HTTP GET request method allowed.'; 
				}
			break;
			
			case 'add_employee':
				if(isTheseParametersAvailable(array('name', 'surname'))){
					$name=$_POST['name'];
					$surname=$_POST['surname'];
					
					$query="INSERT INTO ". EMPLOYEES_TABLE .
							" (". EMPLOYEE_NAME .", ". EMPLOYEE_SURNAME .") VALUES (?, ?)";
					$stmt = $conn->prepare($query);
					$stmt->bind_param("ss", $name, $surname);
					
					if($stmt->execute() && $stmt->affected_rows == 1) {
						$id = $stmt->insert_id;
						$stmt->close();
						$symbol=str_pad("$id", 4, "0", STR_PAD_LEFT);
						$symbol="RXH".$symbol;
						
						$query="UPDATE ". EMPLOYEES_TABLE ." SET ". EMPLOYEE_SYMBOL ." = ? WHERE ". EMPLOYEE_ID ." = ?";
						$stmt = $conn->prepare($query);
						$stmt->bind_param("si", $symbol, $id);
						
						if(!$stmt->execute() && !$stmt->affected_rows == 1) {
							$response['error'] = true; 
							$response['message'] = 'Symbol update query isnt executed properly'; 
						}
						
						$employee = array(
							'id'=>$id, 
							'name'=>$name, 
							'surname'=>$surname,
							'symbol'=>$symbol
						);
						$response['error'] = false; 
						$response['message'] = 'Employee registered successfully'; 
						$response['user'] = $employee; 
					} else {
						$response['error'] = true;
						$response['mysqli_error_message'] = $stmt->error; 
						$response['message'] = 'Insert query isnt executed properly'; 
					}
					$stmt->close();
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;
			
			case 'get_release':
				if(isTheseParametersAvailable(array('release_id'))){
					$releaseId = $_POST['release_id'];
					
					$query="SELECT * FROM ". RELEASES_TABLE ." WHERE ". RELEASES_ID ."?";
					
					$stmt=$conn->prepare($query);
					$stmt->bind_param("i", $releaseId);
					$stmt->execute();
					$stmt->store_result();
					
					if($stmt->num_rows > 0) {
						
					}
					
					$query.="SELECT * FROM ". PRODUCTS_ORDERS_TABLE ." WHERE ". 
						PRODUCTS_ORDERS_ID_RELEASE ."=$releaseId";
					
					/*
					if($conn->multi_query($query)) {
						do {
							if($result = $conn->store_result()) {
								
								$result->free();
							}
						} while ($mysqli->next_result());
						
					} */
					
				}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				}
			break;
			
			
			case 'add_release':
				//if(isTheseParametersAvailable(array('employee_id', 'status'))){
					$json = file_get_contents('php://input');
					// Converts it into a PHP object
					// var_dump(json_decode($json, true));
					$release=json_decode($json);
					//$release=json_decode($json, true);
					//displayJSONObjects($release);
						
					$id_employee=$release->employeeId;
					$status=$release->status;
					$products=$release->productsRelease;
					$surname=$release->Surname;
					
					$c_date=date_create();
					$c_format=$c_date->format('Y-m-d H:i:s');
					
					$query1="INSERT INTO ". RELEASES_TABLE .
						" (". RELEASES_ID_EMPLOYEE ." ,". RELEASES_STATUS ." ,". RELEASES_DATE_CREATION ." ,".
						RELEASES_EMPLOYEE_SURNAME .
						") VALUES (?, ?, ?, ?)";
					// date_format($c_date, 'Y-m-d H:i:s')
					$stmt = $conn->prepare($query1);
					$stmt->bind_param("iiss", $id_employee, $status, $c_format, $surname);
					
					if($stmt->execute() && $stmt->affected_rows == 1) {
						$release_id = $stmt->insert_id;
						$stmt->close();
						
						$query2="INSERT INTO ". PRODUCTS_ORDERS_TABLE .
							" (". PRODUCTS_ORDERS_ID_PRODUCT ." ,". PRODUCTS_ORDERS_ID_RELEASE ." ,". 
							PRODUCTS_ORDERS_STATUS ." ,". PRODUCTS_ORDERS_QUANTITY .
							") VALUES ";
						
						if(count($products)>0) {
							foreach($products as $key => $product) {
								if(!ProductStatus::isValidValue($product->status))
									$product->status = ProductStatus::OCZEKUJĄCY;
								$query2.="($product->id, $release_id, $product->status, $product->quantity),";
							}

							$query2 = rtrim($query2, ","); // usunięcie przecinka na końcu
							echo $query2."\n";
							
							if($conn->query($query2)) {
								$release = array(
									'id'=>$release_id,
									'employeeId'=>$id_employee,
									'productsRelease'=>$products,
									'status'=>$status,
									'creationDate'=>$c_date,
									'Surname'=>$surname
									//'realizationDate'=>null
								);
								
								$response['error'] = false;								
								$response['message'] = 'Release created successfully.'; 
							} else {
								//delete release
								$query1="DELETE FROM". RELEASES_TABLE .
									" WHERE ". RELEASES_ID ." = ". $release_id;
								
								if($conn->query($query1))
									$response['deleteReleaseError']  = false;
								else 
									$response['deleteReleaseError'] = true;
								
								$response['error'] = true;
								$response['mysqli_error_message'] = $conn->error;								
								$response['message'] = 'ProductsReleases isn\'t created successfully.'; 
							}
							$conn->close();
						}
						else {
							$response['error'] = true; 
							$response['message'] = 'required parameter productsRelease is not available'; 
						}
					} else {
						$response['error'] = true;
						$response['mysqli_error_message'] = $stmt->error; 
						$response['message'] = 'The registration for this release failed.';
						$stmt->close();
					}
				/*}else{
					$response['error'] = true; 
					$response['message'] = 'required parameters are not available'; 
				} */
			break;
			
			case 'get_all_releases':
				if(strcmp($_SERVER['REQUEST_METHOD'], 'GET') == 0)
				{
					$query="SELECT * FROM ". RELEASES_TABLE;
					$stmt = $conn->prepare($query);
					$stmt->execute();
					
					$releases = fetchObjects($stmt);
					
					$stmt->close();
					$response['error'] = false; 
					$response['message'] = 'Releases fetched successfully'; 
					$response['object'] = $releases; 
				}
				else {
					$response['error'] = true;
					$response['message'] = 'Only HTTP GET request method allowed.'; 
				}
			break;
		}
	}
	
	echo json_encode($response);
	
	function isTheseParametersAvailable($params){
		
		foreach($params as $param){
			if(!isset($_POST[$param])){
				return false; 
			}
		}
		return true; 
	}
	
	function fetchObjects($stmt) {
		$array = array();
		if($stmt instanceof mysqli_stmt)
		{
			$stmt->store_result();
			
			$vars = array();
			$data = array();
			$meta = $stmt->result_metadata();
		   
			while($field = $meta->fetch_field())
				$vars[] = &$data[$field->name]; // pass by reference
		   
			call_user_func_array(array($stmt, 'bind_result'), $vars);
		   
			$i=0;
			while($stmt->fetch()) {
				$array[$i] = array();
				foreach($data as $k=>$v)
					$array[$i][$k] = $v;
				$i++;
			}
		}
		return $array;
	}
	
	function displayJSONObjects($data) {
		foreach ($data as $k => $v){			
			if(is_array($v)) {
				displayJSONObjects($v);
				echo "--------------------------------\n";
			} else{
				echo $k."=".$data[$k]."\n";
			}
		}
	}
