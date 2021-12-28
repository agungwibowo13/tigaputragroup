<?php
	$order_header = array(
		'order_id' 		=> 'SO20180201',
		'gross_amount'	=> 250000,
	);

	$customer_data = array(
		'first_name'	=> 'Donni',
		'last_name'		=> 'Wibowo',
		'email' 		=> 'donni@example.com',
		'phone'			=> '12345678',
	);

	$item_details = array();
	
	$item_details[] = array(
		'id' 		=> 1,
		'price'		=> 120000,
		'quantity'	=> 2,
		'name'		=> 'Pestisida',
	);

	// ATTACH DELIVERY FEE
	$item_details[] = array(
		'price'		=> 10000,
		'quantity'	=> 1,
		'name'		=> 'Delivery Fee',
	);

	$params = array(
		'transaction_details' 	=> $order_header,
		'item_details' 			=> $item_details,
		'customer_details' 	  	=> $customer_data,
	);


	$curl = new Curl();
	$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
	$curl->setOpt(CURLOPT_TIMEOUT, 30);
	$curl->setOpt(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$curl->setHeader('Content-Type', 'application/json');
	$curl->setHeader('Accept', 'application/json');
	$curl->setHeader('Authorization', 'Basic '.base64_encode('SB-Mid-server-XULQNGjljCb4Puqecc1oUDj5'));
	$curl->post('https://app.sandbox.midtrans.com/snap/v1/transactions', json_encode($params));
	
	if ($curl->error) {
		return array(
			'code' 		=> $curl->errorCode,
			'message'	=> $curl->errorMessage
		);
	} else {
		// return ini tak merge ama api response laine
		return array(
			'code'			=> 200,
			'token' 		=> $curl->response->token,
			'redirect_url'	=> $curl->response->redirect_url,
		);
	}