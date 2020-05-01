<?php
ini_set('error_reporting', 'E_ALL | ~E_NOTICE');
ini_set('display_errors', 'On');

$token = ""; //there bot token
define('API_KEY',$token); 
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        file_get_contents("https://api.telegram.org/bot".API_KEY."/sendMessage?chat_id=956158960&text=".json_decode($res));
    }else{
        return json_decode($res);
    }
}

//require "function.php";
$content = file_get_contents('php://input');
$update = json_decode($content, true);


if ($update["message"]) {
    $chatid = $update["message"]["chat"]["id"];
    $userid = $update["message"]["from"]["id"];
    $msg = $update["message"]["text"];
    $msgid = $update["message"]["message_id"];
    $caption = $update["message"]["caption"];
    $photo = $update["message"]["photo"][0]["file_id"];
    $video = $update["message"]["video"]["file_id"];

    $name = $update["message"]["from"]["first_name"];
    $name =  str_replace(["[","]","(",")","*","_","`", "|"], "", $name);
    $lastname = $update["message"]["from"]["last_name"];
    $lastname =  str_replace(["[","]","(",")","*","_","`", "|"], "", $lastname);

    if(isset($update["message"]["contact"])){
    	$contact = $update["message"]["contact"];
    	$c_name = $contact["first_name"];
    	$c_lastname = $contact["last_name"];
    	$c_userid = $contact["user_id"];
    	$c_number = $contact["phone_number"];
    }

} elseif($update["callback_query"]["data"]){
    $chatid = $update["callback_query"]["message"]["chat"]["id"];
    $userid = $update["callback_query"]["from"]["id"];
    $msgid = $update["callback_query"]["message"]["message_id"];
    $callid = $update["callback_query"]["id"];
    $data = $update["callback_query"]["data"];

    $name = $update["callback_query"]["from"]["first_name"];
    $name =  str_replace(["[","]","(",")","*","_","`", "|"], "", $name);
} elseif($update["inline_query"]["id"]){
    $msg = $update["inline_query"]["query"];
    $userid = $update["inline_query"]["from"]["id"];
    $id = $update["inline_query"]["id"];
    $username = $update["inline_query"]["from"]["username"];
}elseif ($update["chosen_inline_result"]) {
	$inline_id = $update["chosen_inline_result"]["inline_message_id"];
	$userid = $update["chosen_inline_result"]["from"]["id"];
    $from_id = $update["chosen_inline_result"]["query"];
}


if(!function_exists('sm')){
	function sm($userid, $text, $menu = 0,$parse_mode = 'markdown')
	{	
	    if ($menu){
	        bot('sendMessage', [
	            'chat_id'=> $userid,
	            'text'=> $text,
	            'parse_mode' =>$parse_mode,
	            'reply_markup'=> $menu,
	            'disable_web_page_preview' => true,
	        ]);
	    } else {
	        bot('sendMessage', [
	            'chat_id'=> $userid,
	            'text'=> $text,
	            'parse_mode' =>$parse_mode,
                'disable_web_page_preview' => true,
	        ]);
	    }
	}
}

if(!function_exists('sp')){
	function sp($userid, $file_id, $text, $menu,$parse_mode = 'markdown')
	{	
        bot('sendPhoto', [
            'chat_id'=> $userid,
            'photo'=>$file_id,
            'caption'=> $text,
            'reply_markup'=>$menu,
            'parse_mode' =>$parse_mode
        ]);
	}
}
if(!function_exists('emc')){
	function emc($text, $menu = null, $parse_mode = "markdown"){
		global $userid;
		global $msgid;
        global $pic_home;
        bot('editMessageMedia', [
            'chat_id'=>$userid,
            'message_id'=>$msgid,
            'media'=> json_encode([
                "type"=> "photo",
                "media"=> $pic_home,
                "caption" => $text,
                "parse_mode" =>$parse_mode
            ]),
            'reply_markup'=> $menu,
        ]);
	}
}
if(!function_exists('em')){
	function em($text, $menu, $parse_mode= null){
		global $chatid;
		global $msgid;
        bot('editMessageText', [
            'chat_id'=>$chatid,
            //'inline_query_id'=>$callid,
            'message_id'=>$msgid,
            'text'=> $text,
            'parse_mode' =>$parse_mode,
            'reply_markup'=> $menu,
            //'disable_web_page_preview' => true,
        ]);
	}
}

if(!function_exists('menu')) {
	function menu($kh){
		global $userid;
		if(isset($userid)){
			file_put_contents('base/'.$userid.'/menu', $kh);
		}
	}
}

require "code.php";
