<?php
if($msg == "/start"){

	sm($userid, "Hello world, $name", $key);
	$key = json_encode([
		'inline_keyboard'=>[
			[['text'=>'button 1', 'callback_data' => "1"], ['text'=> "button 2", 'callback_data'=> "2"]],
			[['text'=>'button 3', 'callback_data' => "3"], ['text'=> "button 4", 'callback_data'=> "4"]],
		],
	]);

}