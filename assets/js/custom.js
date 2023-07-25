$(document).ready(function(){

	function Generator() {};

	Generator.prototype.rand =  Math.floor(Math.random() * 26) + Date.now();

	Generator.prototype.getId = function() {
	   return this.rand++;
	};
	var idGen =new Generator();
	var orderID = idGen.getId();



	// View Cart

	$.ajax({
		type: "POST",
		data: "viewcart_item="+orderID,
		url: "./parts/product_select.php",
		success: function(result)
		{
			$("#cart_items_box").html(result);
		}
	});

	// Product Select
	function fetch_data()
	{
		$.ajax({
			type: "POST",
			data: "select_product=1",
			url: "./parts/product_select.php",
			success: function(result)
			{
				$("#preloader").hide();
				$("#card_main_box").html(result);
			}
		});
	}
	fetch_data();
	$(document).on("click",".back_btn",function(){
		$("#preloader").show();
		fetch_data();
	});

	$(document).on("click",".card_box",function(){
		var puid = $(this).attr("id");
		$("#preloader").show();
		viewProduct(puid);
	});

	function viewProduct(puid)
	{
		$.ajax({
			type: "POST",
			data: "product_uid="+puid,
			url: "./parts/product_select.php",
			success: function(result)
			{
				$("#preloader").hide();
				$("#card_main_box").html(result);
			}
		});
	}




	$(document).on("click",".cart_icon",function(){
		window.location.href = "view_product.html";
	});

	$(document).on("click",".added_btn",function(){
		window.location.href = "view_product.html";
	});


	$(document).on("click",".addtocart_btn",function(){
		var puid = $(this).attr("id");
		$("#preloader").show();
		$.ajax({
			type: "POST",
			data: "cartproduct_uid="+puid,
			url: "./parts/product_select.php",
			success: function(result)
			{
				$("#preloader").hide();
				window.location.href = "view_product.html";
			}
		});
	});


	$(document).on("click",".dec",function(){
		var proId = $(this).attr("id");
		var realProPrice = parseInt($(this).parent().children(".realProPrice").val());
		var inputValue = parseInt($(this).parent().children(".proPrice").val());
		if (inputValue>1)
		{
			inputValue = inputValue-1;
			$(this).parent().children(".proPrice").val(inputValue);
			$(this).parent().parent().children("div").children(".product_price").text("$"+(realProPrice*inputValue));
			updateQtyValue(inputValue,proId);
		}
	});

	$(document).on("click",".inc",function(){
		var proId = $(this).attr("id");
		var realProPrice = parseInt($(this).parent().children(".realProPrice").val());
		var inputValue = parseInt($(this).parent().children(".proPrice").val());
		inputValue = inputValue+1;
		$(this).parent().children(".proPrice").val(inputValue);
		$(this).parent().parent().children("div").children(".product_price").text("$"+(realProPrice*inputValue));
		updateQtyValue(inputValue,proId);
	});


	function updateQtyValue(qtyValue,proId)
	{
		$("#preloader").show();
		$.ajax({
			type: "POST",
			data: "updateQty="+qtyValue+"&proId="+proId,
			url: "./parts/product_select.php",
			success: function(result)
			{
				$("#preloader").hide();
				window.location.href = "view_product.html";
			}
		});
	}



	$(document).on("click",".remove_cart",function(){
		var proId = $(this).attr("id");
		deleteCartItem(proId)
	});

	function deleteCartItem(proId)
	{
		$("#preloader").show();
		$.ajax({
			type: "POST",
			data: "deleteCartproduct_uid="+proId,
			url: "./parts/product_select.php",
			success: function(result)
			{
				$("#preloader").hide();
				window.location.href = "view_product.html";
			}
		});
	}

		var a = location.href;
		var b = a.substring(a.indexOf(".")+5);

		setTimeout(function(){	
			popupopen();
		},1000);

		function popupopen()
		{
			if(b=='?success')
			{
				$("#address_pop_up").animate({"top":"0"});
			}
		}

	// Address PopUp
	$(document).on("click",".checkout_btn",function(){
		popupopen();
		if(b=='')
		{
			window.location.href = "login.php";
		}
		popupopen();
	});
	$(document).on("click",".popup_close",function(){
		$("#address_pop_up").animate({"top":"-100%"});
	});



	// Address 


	// Email Validation
	var validEmail = false;
	$(document).on('change',"#User_email",function(){
		var emailvalue = $('#User_email').val();
		var checkat = false;
		var checkdot = false;
		var at_index =null;
		var email_space=false;
		var eLength =emailvalue.length;
		
		for(let i=0; i<eLength; i++)
			{
				if(emailvalue[i]==" ")
				{
					email_space=true;
				}
				if(email_space==false)
				{
					email_space = false;
				}
		
				// chech @ in email
				if(emailvalue[i]=="@")
				{
					at_index=i;
					checkat=true;
				}
			}
		var eslice = emailvalue.slice(at_index,eLength);
		for (var i = 0 ; i < eslice.length; i++) 
		{
			// chech . in email
			if(eslice[i]==".")
			{
				checkdot=true;
			}
		}
		if(email_space==false && checkat==true && checkdot==true)
		{
			validEmail = true;
		}
		else
		{
			validEmail = false;
			$("#error_msg").text("Please Enter Valid Email...");
			setTimeout(function(){
				$("#error_msg").text("");
			},4000);
		}
	});


	var validCity = false;
	$(document).on('change',"#user_city",function(){
		var user_city = $("#user_city").val();
		for(i=0;i<user_city.length;i++)
		{
			if(user_city[i]==0 || user_city[i]==1 || user_city[i]==2 || user_city[i]==3 || user_city[i]==4 || user_city[i]==5 || user_city[i]==6 || user_city[i]==7 || user_city[i]==8 || user_city[i]==9)
			{
				validCity=false;
				$("#error_msg").text("Do not use the number in the City option...");
				setTimeout(function(){
					$("#error_msg").text("");
				},4000);
			}
			else
			{
				validCity = true;
			}
		}	
	});

	var user_name = "";
	var user_phone = "";
	var User_email = "";
	var user_address = "";
	var user_city = "";
	var user_zip_code = "";
	$(document).on("click",".conform_checkout_btn",function(){
		user_name = $("#user_name").val();
		user_phone = $("#user_phone").val();
		User_email = $("#User_email").val();
		user_address = $("#user_address").val();
		user_city = $("#user_city").val();
		user_zip_code = $("#user_zip_code").val();
		if(user_name !="" && user_phone != "" && User_email != "" && validEmail == true && user_address != "" && user_city != "" && validCity == true && user_zip_code != "")
		{
			$.ajax({
				type: "POST",
				data: "user_name="+user_name+"&user_phone="+user_phone+"&User_email="+User_email+"&user_address="+user_address+"&user_city="+user_city+"&user_zip_code="+user_zip_code+"&address_info="+orderID,
				url: "./parts/product_select.php",
				success: function(result)
				{
					event.stopPropagation();
				}
			});
		}
		else
		{
			$("#error_msg").text("Please Enter Value...");
			event.preventDefault();
			setTimeout(function(){
				$("#error_msg").text("");
			},4000);
		}
	});
});