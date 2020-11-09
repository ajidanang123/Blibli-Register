<?php

$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:82.0) Gecko/20100101 Firefox/82.0';
$headers[] = 'Accept-Language: en-US,en;q=0.5';
$headers[] = 'Content-Type: application/json;charset=utf-8';

echo color('blue', "[+]")." Blibli Account Creator - By: GidhanB.A\n";
echo color('blue', "[+]")." Verify Phone (y/n): ";
$yn = trim(fgets(STDIN));
echo color('blue', "[+]")." Butuh Berapa: ";
$qty = trim(fgets(STDIN));

for ($i=0; $i < $qty; $i++) { 
	echo "\n";
	$no = $i+1;
	$data = file_get_contents("https://wirkel.com/data.php?qty=1&domain=auspb.com");
	$datas = json_decode($data);
	$nick = $datas->result[0]->username;
	$email = $datas->result[0]->email;
	$pass = "sarkem123";

	echo color('blue', "[$no]")." Email: $email\n";
	Check:
	$reg = curl('https://www.blibli.com/backend/common/users', '{"username":"'.$email.'","password":"'.$pass.'"}', $headers);
	if (strpos($reg[1], '"status":"OK"')) {
		$cookie = http_build_query($reg[2],'','; ');
		echo color('green', "[+]")." Registration Successfuly\n";
		echo color('yellow', "[+]")." Checking Email ";
		$emails = explode("@", $email);
		$emailx = "surl=".trim($emails[1])."%2F".trim($emails[0]);
		$xixi = array();
		$xixi[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:74.0) Gecko/20100101 Firefox/74.0';
		$xixi[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
		$xixi[] = 'Accept-Language: en-US,en;q=0.5';
		$xixi[] = 'Connection: keep-alive';
		$xixi[] = 'Cookie: '.$emailx;
		Awal:
		$xyz = curl('https://generator.email/', null, $xixi, true);
		if (strpos($xyz[1], 'Kalau kamu tidak mendaftarkan akun')) {
			$res = remove_space($xyz[1]);
			$link = get_between($res, '<tdalign="center"valign="middle"><aclass="link"href="', '"style="color:#ffffff;');
			if (empty($link)) die("Error!");
			echo "\n";
		} else {
			echo ".";
			goto Awal;
		}
		array_pop($xixi);
		$xixi[] = 'Cookie: '.$cookie;
		Check1:
		$ver = curl($link, null, $xixi, false);
		if (strpos($ver[1], 'email-verification')) {
			$token = explode('email-verification?code=', $ver[1]);
			$token = explode('&amp;', $token[1]);
			$token = $token[0];
			$xoxo = array();
			$xoxo[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0';
			$xoxo[] = 'Accept: application/json, text/plain, */*';
			$xoxo[] = 'Accept-Language: en-US,en;q=0.5';
			$xoxo[] = 'Content-Type: application/json;charset=utf-8';
			$xoxo[] = 'Connection: keep-alive';
			Check2:
			$verif = curl('https://www.blibli.com/backend/member/email-verification/_verify', '{"logonId":"'.$email.'","token":"'.$token.'"}', $xoxo, false);
			if (strpos($verif[1], '"status":"OK"')) {
				echo color('green', "[+]")." Verification Successfuly\n";
				save($email."|".$pass."\n","akun.txt");
				if ($yn !== 'y') continue;
				Check3:
				$login = curl('https://account.blibli.com/gdn-oauth/token', 'grant_type=password&scope=write&username='.$email.'&password='.$pass.'&client_id=6354c4ea-9fdf-4480-8a0a-b792803a7f11&client_secret=XUQpvvcxxyjfb7KG', $headers, false);
				if (strpos($login[1], '"access_token"')) {
					$ajg = json_decode($login[1]);
					$access = $ajg->access_token;
					$headers[] = 'Authorization: bearer '.$access;
					echo color('blue', "[+]")." Input Number: ";
					$nomer = trim(fgets(STDIN));
					Check4:
					$asu = curl('https://www.blibli.com/backend/mobile/phone-number-verification/token?phoneNumber='.$nomer, null, $headers, false);
					if (strpos($asu[1], '"result":"true"')) {
						echo color('blue', "[+]")." Input OTP: ";
						$otp = trim(fgets(STDIN));
						$json = array('Content-Type: application/x-www-form-urlencoded');
	    				foreach ($json as $val) $headers = str_replace($val, 'Content-Type: application/json', $headers);
						Check5:
						$asw = curl('https://www.blibli.com/backend/mobile/phone-number-verification/verify-token', '{"token":"'.$otp.'"}', $headers, false);
						if (strpos($asw[1], '"result":"true"')) {
							echo color('green', "[+]")." Verification Successfuly\n";
							save($email."|".$pass."\n","akun.txt");
						} elseif ($asu[1] == "HTTP 429 Too Many Requests") {
							goto Check5;
						} else {
							die($asw[1]);
						}
					} elseif ($asu[1] == "HTTP 429 Too Many Requests") {
						goto Check4;
					} else {
						die($asu[1]);
					}
				} elseif ($login[1] == "HTTP 429 Too Many Requests") {
					goto Check3;
				} else {
					die($login[1]);
				}
			} elseif ($verif[1] == "HTTP 429 Too Many Requests") {
				goto Check2;
			} else {
				die($verif[1]);
			}
		} elseif ($ver[1] == "HTTP 429 Too Many Requests") {
			goto Check1;
		} else {
			die($ver[1]);
		}
	} elseif (strpos($reg[1], 'REJECTED_LOGON_ID')) {
		echo color('red', "[+]")." Error, Domain Banned!\n";
		die();
	} elseif ($reg[1] == "HTTP 429 Too Many Requests") {
		goto Check;
	} else {
		die($reg[1]);
	}
}

function curl($url,$post,$headers,$follow=false,$method=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($follow == true) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($method !== null) curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) {
		  parse_str($item, $cookie);
		  $cookies = array_merge($cookies, $cookie);
		}
		return array (
		$header,
		$body,
		$cookies
		);
	}

function get_between($string, $start, $end) 
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

function remove_space($var) {
    $new = str_replace("\n", "", $var);
    $new = str_replace("\t", "", $new);
    $new = str_replace(" ", "", $new);
    return $new;
}

function color($color = "default" , $text)
    {
        $arrayColor = array(
            'red'       => '1;31',
            'green'     => '1;32',
            'yellow'    => '1;33',
            'blue'      => '1;34',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }

function save($data, $file) 
	{
		$handle = fopen($file, 'a+');
		fwrite($handle, $data);
		fclose($handle);
	}