<?php
require __DIR__ . '/vendor/autoload.php';
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;

// set false for production
// set LINE channel_access_token and channel_secret
$channel_access_token = "YOUR_ACCESS_TOKEN";
$channel_secret = "YOUR_SECRET_TOKEN";

// inisiasi objek bot
$httpClient = new CurlHTTPClient($channel_access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channel_secret]);

$configs =  [
    'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);

// buat route untuk url homepage
$app->get('/', function($req, $res)
{
  echo "Welcome at Slim Framework";
});

$pass_signature = "";

// buat route untuk webhook
$app->post('/webhook', function ($request, $response) use ($bot, $pass_signature)
{
    // get request body and line signature header
    $body = file_get_contents('php://input');
    // kode aplikasi nanti disini
    
	$data = json_decode($body, true);
	
    
if(is_array($data['events'])){
    foreach ($data['events'] as $event)
    {		
        if ($event['type'] == 'message')
        {
            $_SESSION['id'] = $event['source']['userId'];
            $session = $_SESSION['id'];
            
			$pesan_masuk = 	strtolower($event['message']['text']);
			$sub = substr("$pesan_masuk",0,3);
			$sub2 = substr("$pesan_masuk",0,2);
            $sub3 = substr("$pesan_masuk",0,4);
            $sub4 = substr("$pesan_masuk",0,5);
			    if($sub == "php"){
	
    			    $response2 = $bot->getProfile($event['source']['userId']);
                    $profile = $response2->getJSONDecodedBody();
                    $user = $profile['userId'];
                    $code = $event['message']['text'];
                    $code2 = substr("$code",4);
        			file_put_contents('./.code/'.$user.'.php',$code2);
        			$output = escapeshellcmd(escapeshellarg("./$user.php"));
        			$output2 = shell_exec("php .code/$output");
        			$error = str_replace("/home/massurya/public_html/wordpress/runcode/.code/$user.php","[...][...]","$output2");
        			shell_exec("rm .code/*");
        			$res = $bot->replyText($event['replyToken'],"$error");
			    }
			    else if($sub2 == "py"){
			        
			        $response2 = $bot->getProfile($event['source']['userId']);
                    $profile = $response2->getJSONDecodedBody();
                    $user = $profile['userId'];
                    $code = $event['message']['text'];
                    $code2 = substr("$code",3);
    			    file_put_contents('./.code/'.$user.'.py',$code2);
    			    $output = escapeshellcmd(escapeshellarg(".code/$user.py"));
                    $output2 = shell_exec("python .code/$user.py 2>&1");
                    $error = str_replace(".code/$user.py","[...][...]","$output2");
                    
                   shell_exec("rm .code/*");
                    
                    $res = $bot->replyText($event['replyToken'],"$error");
                 
			    }
			    else if($sub3 == "java"){
			        unlink("error_log");
			        $response2 = $bot->getProfile($event['source']['userId']);
                    $profile = $response2->getJSONDecodedBody();
                    $user = $profile['userId'];
                    $code = $event['message']['text'];
                    $code2 = substr("$code",5);
    			  
                    file_put_contents('./.code/'.$user.'.java',$code2);
                  
                    $s1 = shell_exec("strings .code/$user.java | grep 'public class'");
                    $s2 = substr("$s1",13);
                    $s3 = trim(str_replace("{","",$s2));
                    file_put_contents('./.code/'.$s3.'.java',$code2);
                    $err = shell_exec("javac .code/$s3.java 2>&1");
                    $output = shell_exec("java -Xmx128M -Xms16M -classpath .code/ $s3");
                    shell_exec("rm .code/*");
                   $res = $bot->replyText($event['replyToken'],"$err $output");
			    }
			        else if($sub == "cpp"){
			        unlink("error_log");
			        $response2 = $bot->getProfile($event['source']['userId']);
                    $profile = $response2->getJSONDecodedBody();
                    $user = $profile['userId'];
                    $code = $event['message']['text'];
                    $code2 = substr("$code",4);
                    file_put_contents('./.code/'.$user.'.cpp',$code2);
                    $err = shell_exec("g++ .code/$user.cpp -o .code/$user 2>&1");
                    $error = str_replace(".code/$user.cpp","[...][...]","$err");
                    $output = shell_exec(".code/./$user");
                    
                    shell_exec("rm .code/*");
                    
                   $res = $bot->replyText($event['replyToken'],"$error $output");
                   
			    } else if($sub4 == "clang"){
			        unlink("error_log");
			        $response2 = $bot->getProfile($event['source']['userId']);
                    $profile = $response2->getJSONDecodedBody();
                    $user = $profile['userId'];
                    $code = $event['message']['text'];
                    $code2 = substr("$code",6);
                    file_put_contents('./.code/'.$user.'.c',$code2);
                    $err = shell_exec("gcc .code/$user.c -o .code/$user 2>&1");
                    $error = str_replace("$user.c","[...][...]","$err");
                    $output = shell_exec(".code/./$user");
                    shell_exec("rm .code/*");
                   $res = $bot->replyText($event['replyToken'],"$error $output");
			    }
}
}
}
}
);

$app->run();
