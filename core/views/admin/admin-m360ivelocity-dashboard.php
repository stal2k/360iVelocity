<?php

function GetAuth($email, $pass) { $pass = urlencode($pass);

      $ch = curl_init("http://velocity.mashable.com/user_sessions?user_sessions?utf8=✓&authenticity_token=ONYaxdpbHooz1myE4vIz%2B3BXWjyIkDTX0XCj46WdbPI%3D&email=greg.garritani%40360i.com&password=leanmove34&commit=Log+In");    

      $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; 

      $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us'; 

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");

      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10); curl_setopt($ch, CURLOPT_TIMEOUT, 10);

      curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);

      global curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $result = curl_exec($ch); $resultArray = curl_getinfo($ch); $errmsg = curl_error($ch); 

      if (trim($errmsg)!=='' || (is_array($resultArray) && $resultArray['http_code']!='200')) return array('result'=>'Error', 'error'=>"Error: ".$resultArray['http_code']." | Invalid Login ".$errmsg);

      curl_close($ch); $arr = explode("=",$result); $token = $arr[3]; if (trim($token)=='') return false; else return $token;

    }

    function Post($auth, $blogID, $title, $text) {$text = str_ireplace('allowfullscreen','', $text); $title = utf8_decode(strip_tags($title)); 

      $text = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', "", $text);  $text = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', "", $text); $text = utf8_decode($text); 

    

      $postText = '<entry xmlns="http://www.w3.org/2005/Atom"><title type="text">'.$title.'</title><content type="xhtml">'.$text.'</content></entry>'; //prr($postText);

      $ch = curl_init("https://www.blogger.com/feeds/$blogID/posts/default"); 

      $headers = array("Content-type: application/atom+xml", "Content-Length: ".strlen($postText), "Authorization: GoogleLogin auth=".$auth, $postText);

      curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1);  curl_setopt($ch, CURLOPT_POST, true);  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0)");

      curl_setopt($ch, CURLOPT_HEADER,0); curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1); curl_setopt($ch, CURLINFO_HEADER_OUT, true); 

      global $nxs_skipSSLCheck; if ($nxs_skipSSLCheck===true) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $result = curl_exec($ch); curl_close($ch); 

      if (stripos($result,'tag:blogger.com')!==false) { $postID = CutFromTo($result, " rel='alternate' type='text/html' href='", "'"); return array("code"=>"OK", "post_id"=>$postID); } else return array("code"=>"ERR", "error"=>$result); 

    }


// $args = array(
//'headers' => array(
//'Authorization' => 'Basic ' . base64_encode( 'greg.garritani@360i.com' . ':' . 'leanmove34' )
//)
//);
//wp_remote_post( 'http://velocity.mashable.com/user_sessions', $args );
?>


<div class="wrap">
	<div class="m360ivelocity">
		<div class="icon32" id="360i-logo"><br></div>
		<h2><?php _e( '360i Velocity Dashboard', M360IVELOCITY_DOMAIN ); ?></h2>
		<iframe id="m360ivelocity-iframe" src="http://velocity.mashable.com/"></iframe>
	</div>
</div>
