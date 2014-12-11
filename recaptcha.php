<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>reCaptcha</title>

<!--reCaptcha-->
<script src='https://www.google.com/recaptcha/api.js'></script>
<!--/reCaptcha-->

</head>
<body>
  <form method="post" action="">
    <p>Komentář: <input type="text" name="komentar"></p>
    
    <!--reCaptcha-->
    <div class="g-recaptcha" data-sitekey="####################"></div>
    <!--/reCaptcha-->
    
    <input type="Submit" value="Odeslat">
  </form>
<?php
//cURL funkce 
function getCurlData($url) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//ne uplne ok
  $curlData = curl_exec($curl);
  curl_close($curl);
  return $curlData;
}

if (!empty($_POST)) {

//ověření reCaptcha
$recaptcha=$_POST['g-recaptcha-response'];
  if (!empty($recaptcha)) {
  $google_url="https://www.google.com/recaptcha/api/siteverify";
  $secret_key='********************';
  $ip=$_SERVER['REMOTE_ADDR'];
  $url=$google_url."?secret=".$secret_key."&response=".$recaptcha."&remoteip=".$ip;
  $res=getCurlData($url);
  $res= json_decode($res, true);
    if ($res['success']) {
    echo "<p>Jste člověk!</p>";
    //...
    }
    else {
    echo "<p>Ověření bylo neúspěšné. Prosím zkuste to znovu.</p>";
    }
  }

}
?>
</body>
</html>