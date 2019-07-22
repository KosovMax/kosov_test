<?php 
	
	$json = json_decode($_REQUEST['name'], true);

	$out = '';

	if(count($json) == 0){
		die("Not array!!!");
	}

	foreach ($json as $k => $v) {

		if($v['filed'] == ''){
			continue;
		}
		if($v['operator'] == ''){
			continue;
		}
		if($v['value'] == ''){
			continue;
		}

		$sort = $v['filed'];
		$value = $v['value'];
		$operator = ':';

		switch ($v['operator']) {
			case '<':
				$operator = ':<';
				break;
			case '>=':
				$operator = ':>=';
				break;
			case '==':
				$operator = ':';
				break;
		}

		$ch = curl_init();

		$url = "https://api.github.com/search/repositories?q=".$sort.$operator.$value."+language:go&sort=".$sort."&order=desc";

		curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_URL, "https://api.github.com/search/repositories");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$headers = array();
		$headers[] = "User-Agent: request";

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		// $out .= curl_exec($ch);

		$js = json_decode(curl_exec($ch), true);
		$js['F_URL'] = $url;
		$js['F_FILED'] = $v['filed'];
		$js['F_OPERATOR'] = $v['operator'];
		$js['F_VALUE'] = $v['value'];

		$out = json_encode($js);
	}

	print_r($out);

 ?>