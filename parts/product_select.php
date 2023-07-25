<?php 
	session_start(); 
	include '../helper/db_connection.php';
	if(isset($_SESSION['uid'])){
		//
	} else {
		$_SESSION['uid'] = session_id();
	}

	$paypal_email = "stevenb2001@outlook.com";
	$paypal_name = "cyber_project";
	$notify_url = "http://localhost/cyber_project/conform_order.html";
	$cancel_return = "http://localhost/cyber_project/view_product.html";
	$return = "http://localhost/cyber_project/view_product.html";


    if (isset($_POST['select_product'])) {
		$output = '';
		$sql = "SELECT * FROM `products` WHERE is_deleted=0";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
		    $output .= '<div class="card_box" id="'.$row['uid'].'">
		    			<div class="product_image_box">
		    				<img src="./assets/images/'.$row['product_image_url'].'" alt="Product Image">
		    			</div>
						<div class="product_details_main_box">
							<h2 class="product_name">'.$row['product_name'].'</h2>
							<span class="product_price"><strike>$'.$row['product_price'].'</strike> | $'.$row['product_sale_price'].'</span>
						</div>
					</div>';
		  }
		} else {
		  echo "0 Result";
		}
		echo $output;
	}

	if (isset($_POST['product_uid'])) {
		$output = '';
		$sql = "SELECT * FROM `products` WHERE is_deleted=0 AND uid=".$_POST['product_uid'];
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  while($row = $result->fetch_assoc()) {
		    $output .= '<div class="view_product_page">
		    			<div class="back_btn"><i class="fa-solid fa-arrow-left"></i></div>
						<div class="product_details_box">
							<div class="product_image">
								<img src="./assets/images/'.$row['product_image_url'].'" alt="Product_image">
							</div>
							<div class="product_details">
								<h2 class="product_name">'.$row['product_name'].'</h2>
								<div class="product_discription">'.$row['product_description'].'</div>
								<span class="product_price"><strike>$'.$row['product_price'].'</strike> | $'.$row['product_sale_price'].'</span>
								<div class="btns_box">';
									// $sql1 = "SELECT * FROM `cart_item` WHERE `is_deleted`=0 AND `product_id`=".$_POST['product_uid'];
									// $result1 = $conn->query($sql1);

									// if ($result1->num_rows > 0) {
									//   while($row = $result1->fetch_assoc()) {
									//     $output .= '<button class="added_btn">Added</button>';
									//   }
									// } else {
										$output .= '<button class="addtocart_btn" id="'.$row['uid'].'">Add to Cart</button>';
									// }
								$output .= '</div>
							</div>
						</div>
						</div>';
		  }
		} else {
		  echo "0 Result";
		}
		echo $output;
	}


	if (isset($_POST['cartproduct_uid'])) {
		$sql = "SELECT * FROM `cart_item` WHERE `is_deleted`=0 AND `product_id`=".$_POST['cartproduct_uid']." AND `session_id`='".$_SESSION['uid']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row

		  while($row = $result->fetch_assoc()) {
		  	$sql1 = "UPDATE `cart_item` SET `qty`='".($row['qty']+1)."' WHERE `product_id`='".$row['product_id']."'";
			if ($conn->query($sql1) === TRUE) {
			  //echo "New record created successfully";
			} else {
			  //echo "Error: " . $sql . "<br>" . $conn->error;
			}
		  }
		} else {
		  $sql1 = "INSERT INTO `cart_item`(`session_id`, `product_id`, `qty`) VALUES ('".$_SESSION['uid']."','".$_POST['cartproduct_uid']."','1')";
			if ($conn->query($sql1) === TRUE) {
			  //echo "New record created successfully";
			} else {
			  //echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		echo $output;
	}

	// Cart View
	if (isset($_POST['viewcart_item']))
	{
		$output = '';

		$sql = "SELECT * FROM `cart_item` WHERE `is_deleted`=0 AND `session_id`='".$_SESSION['uid']."'";
		$result = $conn->query($sql);

		$totalPrice = 0;
		if ($result->num_rows > 0) {
		  // output data of each row
			$totalPrice1 = 0;
			$output .= '<div class="card_items_box_element">';
		  while($row = $result->fetch_assoc()) {
		  	$sql1 = "SELECT * FROM `products` WHERE `is_deleted`=0 AND `uid`='".$row['product_id']."'";
			$result1 = $conn->query($sql1);
			if ($result1->num_rows > 0) {
			  // output data of each row

			  while($row1 = $result1->fetch_assoc()) {
			  	$totalPrice1 = $row['qty']*$row1['product_sale_price'];
			    $output .= '<div class="cart_items_col">
								<img src="./assets/images/'.$row1['product_image_url'].'" alt="product_image" class="cart_product_image">
								<div class="cart_product_details">
									<div class="remove_cart" id="'.$row1['uid'].'"><i class="fa-solid fa-trash"></i></div>
									<span class="cart_product_name">'.$row1['product_name'].'</span>
									<div>
										<span class="product_price" id="product_price">$'.number_format($row['qty']*$row1['product_sale_price']).'</span>
									</div>
									<div class="qty_element">
										<input class="realProPrice" type="hidden" name="rPrice" value="'.$row1['product_sale_price'].'">
										<button class="dec" id="'.$row1['uid'].'">-</button>
										<input class="proPrice" type="number" name="qty" value="'.$row['qty'].'" min="0" disabled>
										<button class="inc" id="'.$row1['uid'].'">+</button>
									</div>
								</div>
							</div>
							';
			  }
			} else {

			}
			$totalPrice = $totalPrice+ $totalPrice1;
		  }
		} else {
		  echo "0 results";
		}
		$output .= '</div><div class="totalPriceElement">
			  	<span>Total Price : $'.number_format($totalPrice).'</span><br>
			  	<button type="submit" class="checkout_btn" id="checkout_btn">Login & Checkout</button>
			  	<div class="paypal_image">
			  		<img src="./assets/images/paypal.png" alt="paypal">
			  	</div>
			  </div>';
		$output .= '<div class="address_pop_up" id="address_pop_up">
			<div class="address_form">
				<h2>Checkout</h2>
				<p>Shipping Address</p>
				<div class="input_box">
					<label for="user_name">Name  </label>
					<input type="text" name="user_name" value="" id="user_name" placeholder="Enter Your Name..." class="input_data">
				</div>
				<div class="input_box">
					<label for="user_phone">Phone  </label>
					<input type="number" name="user_phone" value="" id="user_phone" placeholder="Enter Your Phone No..." class="input_data">
				</div>
				<div class="input_box">
					<label for="User_email">Email  </label>
					<input type="text" name="User_email" value="" id="User_email" placeholder="Enter Your Email..." class="input_data">
				</div>
				<div class="input_box">
					<label for="user_address">Address </label>
					<input type="text" name="user_address" value="" id="user_address" placeholder="Enter Address..." class="input_data">
				</div>
				<div class="input_box">
					<label for="user_city">City </label>
					<input type="text" name="user_city" value="" id="user_city" placeholder="Enter City..." class="input_data">
				</div>
				<div class="input_box">
					<label for="user_zip_code">ZIP Code  </label>
					<input type="number" name="user_zip_code" value="" id="user_zip_code" placeholder="Enter ZIP Code..." class="input_data">
				</div>
				<div id="error_msg"></div>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="business" value="'.$paypal_email.'" id="paypal_email">
					<input type="hidden" name="item_name" value="'.$paypal_name.'" id="paypal_name">
					<input type="hidden" name="item_number" id="order_id" value="'.$_POST['viewcart_item'].'">
					<input type="hidden" name="amount" value="'.$totalPrice.'" id="total_pricing">
					<input type="hidden" name="no_shipping" value="1">
					<input type="hidden" name="currency_code" value="USD" id="currency">
					<input type="hidden" name="notify_url" id="notify_url" value="'.$notify_url.'?id='.$_POST['viewcart_item'].'">
					<input type="hidden" name="cancel_return" id="cancel_return" value="'.$cancel_return.'">
					<input type="hidden" name="return" id="return" value="'.$return.'">
					<input type="hidden" name="cmd" value="_xclick">
					<div class="conform_checkout_btn_box">
						<input type="submit" name="pay_now" id="pay_now" value="Pay With Paypal" class="conform_checkout_btn">
					</div>
				</form>
			  	<div class="paypal_image2">
			  		<img src="./assets/images/paypal.png" alt="paypal">
			  	</div>
				<div class="popup_close">
					<i class="fa-sharp fa-solid fa-xmark"></i>
				</div>
			</div>
		</div>';
		echo $output;
	}


	if (isset($_POST['deleteCartproduct_uid'])) {
		
		// sql to delete a record
		$sql = "DELETE FROM `cart_item` WHERE product_id=".$_POST['deleteCartproduct_uid'];

		if ($conn->query($sql) === TRUE) {
		  //echo "Record deleted successfully";
		} else {
		  //echo "Error deleting record: " . $conn->error;
		}
	}


	if (isset($_POST['updateQty'])) {
		$sql1 = "UPDATE `cart_item` SET `qty`='".$_POST['updateQty']."' WHERE `product_id`=".$_POST['proId'];
		if ($conn->query($sql1) === TRUE) {
		  //echo "New record created successfully";
		} else {
		  //echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}



	// Address Info
	if (isset($_POST['address_info'])) {
		$sql1 = "SELECT * FROM `address_info` WHERE `order_id`=".$_POST['address_info'];
			$result1 = $conn->query($sql1);
			if ($result1->num_rows > 0) {
			  // output data of each row
			  while($row1 = $result1->fetch_assoc()) {
			  	$sql2 = "UPDATE `address_info` SET `name`='".mysqli_real_escape_string($conn, $_POST['user_name'])."',`phone`='".mysqli_real_escape_string($conn, $_POST['user_phone'])."',`email`='".mysqli_real_escape_string($conn, $_POST['User_email'])."',`address`='".mysqli_real_escape_string($conn, $_POST['user_address'])."',`city`='".mysqli_real_escape_string($conn, $_POST['user_city'])."',`zip_code`='".mysqli_real_escape_string($conn, $_POST['user_zip_code'])."',`session_id`='".$_SESSION['uid']."' WHERE `order_id`=".$_POST['address_info'];

				if ($conn->query($sql2) === TRUE) {
				  //echo "Record updated successfully";
				} else {
				  //echo "Error updating record: " . $conn->error;
				}
			  }
			} else {
				$sql = "INSERT INTO `address_info`(`name`, `phone`, `email`, `address`, `city`, `zip_code`, `order_id`, `session_id`) VALUES ('".mysqli_real_escape_string($conn, $_POST['user_name'])."','".mysqli_real_escape_string($conn, $_POST['user_phone'])."','".mysqli_real_escape_string($conn, $_POST['User_email'])."','".mysqli_real_escape_string($conn, $_POST['user_address'])."','".mysqli_real_escape_string($conn, $_POST['user_city'])."','".mysqli_real_escape_string($conn, $_POST['user_zip_code'])."','".mysqli_real_escape_string($conn, $_POST['address_info'])."','".$_SESSION['uid']."')";

				if ($conn->query($sql) === TRUE) {
				  //echo "New record created successfully";
				} else {
				  //echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
	}
	$conn->close();
?>