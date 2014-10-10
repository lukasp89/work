<?php
session_start();
if (!empty($_SESSION['zalogovan'])) {
$zalogovan=$_SESSION['zalogovan'];
setcookie("zalogovan", $zalogovan, time()+259200);
}
else {
  if (!empty($_COOKIE['zalogovan'])) {
   $_SESSION['zalogovan']=$_COOKIE['zalogovan'];
  }
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Filex browser</title>
<link rel="stylesheet" type="text/css" href="flowplayer/skin/minimalist.css">
<link href="default.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="swfupload.js"></script>
<script type="text/javascript" src="plugins/swfupload.queue.js"></script>
<script type="text/javascript" src="plugins/swfupload.speed.js"></script>
<script type="text/javascript" src="plugins/swfupload.swfobject.js"></script>
<script type="text/javascript" src="plugins/swfupload.cookies.js"></script>
<script type="text/javascript" src="fileprogress.js"></script>
<script type="text/javascript" src="handlers.js"></script>
<script type="text/javascript" src="jquery202.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    $('#ajax').load('vypism.php');
  } else { $('#ajax').load('vypis.php'); } 
});

		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "Flash/swfupload.swf",
				upload_url: <?php if (!empty($slozka)) {echo "\"upload.php?slozka=$slozka\",";} else echo "\"upload.php\",";?>
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "TestImageNoText_65x29.png",
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">Nahraj</span>',
				button_text_style: ".theFont { font-size: 16; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};      

			swfu = new SWFUpload(settings);
	     };
	</script>
</head>
<body onbeforeunload="predukonc()">
<?php if ($_SESSION['zalogovan']!=1): ?>
<form method="post" action="">
<p>Heslo:<input class="login" type="password" name="pass"></p>
<input type="hidden" name="check" value="1">
<p><input type="submit" value="Přihlásit"></p>
</form>
<?php
$pass = sha1($_POST['pass']);
$check = $_POST['check'];
if ($check == 1) {
  if (!empty($_POST['pass'])) {
    if ($pass=="ae57583a9a335f3e9f71b42bfd812b4cca793142") {
    session_start();
    $_SESSION['zalogovan']=1;
    echo "<meta http-equiv=refresh content=\"1;url=index.php\">";
    } else {
    echo "<p>Špatné heslo!</p>";
    }
  } else echo "<p>Vyplň heslo!</p>";
}
?>
<?php endif; ?>

<?php if ($_SESSION['zalogovan']==1): ?>                
<div id="content">
	<h1>Filex</h1>
	<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">Soubory ve frontě</span>
			</div>
		  <div id="divStatus">0 Files Uploaded</div>   
			<div>
				<span id="spanButtonPlaceHolder"></span>
				<input id="btnCancel" type="button" value="Zrušit všechna nahrávání" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;">
			</div>

	</form>
  <div class="full">
    <div class="full1">
      <div class="left">
<?php
if (!empty($_GET['slozka']))  {
  $slozkab = trim($_GET['slozka']);
  if (!empty($slozkab))  {
  $slozka = ($_GET['slozka']) . "/";
  session_start();
  $_SESSION['slozka']=$slozka;  
  }
  else { 
  $slozka=NULL;    
  session_start();
  $_SESSION['slozka']=$slozka; 
  }
}
else {
$slozka=NULL;
session_start();
$_SESSION['slozka']=$slozka; 
}

if (!empty($slozka)) {
$pocsloz = substr_count(substr($slozka,0,-1), "/");
  if ($pocsloz>0) {
  $posllom = strrpos(substr($slozka,0,-1), "/");
  $vys = substr($slozka,0,$posllom);
  echo "      <a href=\"index.php?slozka=$vys\">O úroveň výš</a>";
  } else echo "      <a href=\"index.php\">O úroveň výš</a>";
}
?>      
      <p class="novaslozka" id="novaslozkatl">Nová složka</p>
      </div>
      <div class="right roztahnutelne">
        <input id="vyhlpole" type="text" value="">
        <input id="vyhledat" type="submit" value="">
      </div>   
    </div>
    <div class="full1">
      <div class="left">
<?php
echo "      <p>http://filex.bravoforce.cz/$slozka</p>\n";
?>
      </div>
    </div>
    <div id="ajax">
    <noscript>
    <?php
    include("vypis.php");
    ?>
    </noscript> 
    </div>
  </div>
  <div class="full">
  <a href="logout.php">Odhlásit</a>
  </div>  

<?php endif; ?>
<div id="novaslozka" style="display: none">
<form method="post" action="novaslozka.php">
<p>Název složky: <input type="text" name="novaslozka" value="">
<input type="hidden" name="check" value="1">
<input type="hidden" name="cesta" value="<?php echo $slozka; ?>"></p>
<p><input type="submit" value="Vytvoř">
<input type="button" class="roztahnutelne" id="novaslozkacncl" value="Storno"></p>
</form>
</div>
<script type="text/javascript">
function predukonc() {
  $.post("vymaz.php", { deltempadr: '1' })
  .done(function() {
  return false;
  });   
}

$('#novaslozkatl, #novaslozkacncl').click(function(){
if (document.getElementById("novaslozka")) {
      if (document.getElementById("novaslozka").style.display == "none") {
         document.getElementById("novaslozka").style.display = "";
      }
      else {
         document.getElementById("novaslozka").style.display = "none";
      }
   }
   return false;  
});   
</script>             
</div>

</body>
</html>