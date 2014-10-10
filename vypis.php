<?php  
session_start();
$slozka = $_SESSION['slozka'];    
if (!empty($_GET['vyhledat'])){
  $vyhledatb = trim($_GET['vyhledat']);
  if (!empty($vyhledatb))  {
  $vyhledat = $_GET['vyhledat'];
  } else $vyhledat=NULL; 
} else $vyhledat=NULL;

//adopted function dirsize
function dirsize($path) {
  // Init
  $size = 0;

  // Trailing slash
  if (substr($path, -1, 1) !== DIRECTORY_SEPARATOR) {
      $path .= DIRECTORY_SEPARATOR;
  }

  // Sanity check
  if (is_file($path)) {
      return filesize($path);
  } elseif (!is_dir($path)) {
      return false;
  }

  // Iterate queue
  $queue = array($path);
  for ($i = 0, $j = count($queue); $i < $j; ++$i)
  {
      // Open directory
      $parent = $i;
      if (is_dir($queue[$i]) && $dir = @dir($queue[$i])) {
          $subdirs = array();
          while (false !== ($entry = $dir->read())) {
              // Skip pointers
              if ($entry == '.' || $entry == '..') {
                  continue;
              }

              // Get list of directories or filesizes
              $path = $queue[$i] . $entry;
              if (is_dir($path)) {
                  $path .= DIRECTORY_SEPARATOR;
                  $subdirs[] = $path;
              } elseif (is_file($path)) {
                  $size += filesize($path);
              }
          }

          // Add subdirectories to start of queue
          unset($queue[0]);
          $queue = array_merge($subdirs, $queue);

          // Recalculate stack size
          $i = -1;
          $j = count($queue);

          // Clean up
          $dir->close();
          unset($dir);
      }
  }

  return $size;
}

$adres="../$slozka";
$num=0;
$soubret1 = array();  
$cdir = scandir($adres);
foreach ($cdir as $value) {
  if ($value != "." and $value != "..") {
    if (substr_count($value, '.') == 0) {
      array_push($soubret1, "$value");
      $num+=1;
    }
  }
}
usort($soubret1, 'strcasecmp');

$soubret2 = array();  
$cdir2 = scandir($adres);
foreach ($cdir2 as $value2) {
  if ($value2 != "." and $value2 != "..") {
    if (substr_count($value2, '.') > 0) {
      array_push($soubret2, "$value2");
      $num+=1;    
    }
  }
}
usort($soubret2, 'strcasecmp');

$soubret = array_merge($soubret1, $soubret2);

$nummm=$num+2;
$sloupec=ceil($num/3);
$sloupec2=($sloupec*2);

$sloup1 = array();
$sloup2 = array();
$sloup3 = array();

for ($v=0;$v<$num;$v++) {
$soubor3 = $soubret[$v];
  if ($v<$sloupec) {
  array_push($sloup1, $soubor3);
  }
  if (($v>=$sloupec) and ($v<$sloupec2)) {
  array_push($sloup2, $soubor3);
  }
  if ($v>=$sloupec2) {               
  array_push($sloup3, $soubor3);
  }
}

$float = array();

for ($w=0;$w<$sloupec;$w++) {
if (!empty($sloup1[$w])){
$sl1 = $sloup1[$w];
} else $sl1 = "blank";

if (!empty($sloup2[$w])){
$sl2 = $sloup2[$w];
} else $sl2 = "blank";

if (!empty($sloup3[$w])){
$sl3 = $sloup3[$w];
} else $sl3 = "blank";

array_push($float, $sl1);
array_push($float, $sl2);
array_push($float, $sl3);
}


function ikona($soubor, $adres) {
$obrazky = array('.jpg','.JPG','.png','.bmp', '.gif');
$vyslpocobr = 0;
  foreach ($obrazky as $pripona) {
    $vyslpocobr+=substr_count($soubor, $pripona);
  }
  if ($vyslpocobr > 0) {
  echo "<img onclick=\"zobrnahl(&quot;$adres$soubor&quot;)\" class=\"zobrnahl\" src=\"imgicon.png\" alt=\"$adres$soubor\">";
  }

  if (substr_count($soubor, '.') == 0) {
  echo "<img class=\"zobrnahl\" src=\"foldicon.png\" alt=\"$adres$soubor\">";
  }
  
$videa = array('.mp4','.ogv','.flv');
$vyslpocvid = 0;
  foreach ($videa as $pripona) {
    $vyslpocvid+=substr_count($soubor, $pripona);
    if ($vyslpocvid==1) {$typvidea = $pripona;}
  }  
  if($vyslpocvid > 0) {
  echo "<img onclick=\"zobrnahlvid(&quot;$adres$soubor&quot;)\" class=\"zobrnahl\" src=\"vidicon.png\" alt=\"$adres$soubor\">";
  }
  
$office = array('.docx','.xlsx','.pptx','.doc','.xls','.ppt');
$vyslpocofic = 0;
  foreach ($office as $pripona) {
    $vyslpocofic+=substr_count($soubor, $pripona);
  }  
  if($vyslpocofic > 0) {
  $slozneabs = substr($adres, 3);
  echo "<img onclick=\"zobrnahloffice(&quot;http://f.bravoforce.cz/$slozneabs$soubor&quot;)\" class=\"zobrnahl\" src=\"officeicon.png\" alt=\"http://f.bravoforce.cz/$slozneabs$soubor\">";
  }   

$vyslpocphp=substr_count($soubor, '.php');
  if($vyslpocphp > 0) {
  echo "<img onclick=\"zobrnahlphp(&quot;$adres$soubor&quot;)\" class=\"zobrnahl\" src=\"phpicon.png\" alt=\"$adres$soubor\">";
  }
  
$vyslpoczip=substr_count($soubor, '.zip');
  if($vyslpoczip > 0) {
  echo "<img onclick=\"zobrnahlzip(&quot;$adres$soubor&quot;)\" class=\"zobrnahl\" src=\"zipicon.png\" alt=\"$adres$soubor\">";
  }  
}
echo "<div id=\"selectable\">\n";

$konecnypocet = $sloupec*3;
for ($o=0;$o<$konecnypocet;$o++) {
$soubor = $float[$o];
  if ($soubor != "blank") {
    if (empty($vyhledat)) {
      echo "<p class=\"soubor ui-selectee\" id=\"checkbox$o\">";      
      ikona($soubor, $adres);
      if (substr_count($soubor, '.') > 0) {    
      echo "<a href=\""; echo ("$adres"); echo ($soubor);
      echo "\" target=\"_blank\">"; echo ($soubor); echo"</a><input class=\"checkb\" type=\"checkbox\" name=\"checksoub[]\" value=\"$soubor\"> ";
      echo "<span class=\"velsoub\">" . round(((filesize("$adres/$soubor"))/1024),2) . " kB"; echo "</span>";
      echo "</p>\n";
      }
      if (substr_count($soubor, '.') == 0) {    
      echo "<a href=\""; echo ("index.php?slozka=$slozka");
      echo ($soubor); echo "\">"; echo ($soubor); echo"</a><input class=\"checkb\" type=\"checkbox\" name=\"checksoub[]\" value=\"$soubor\"> ";
      echo "<span class=\"velsoub\">" . round(((dirsize("$adres/$soubor"))/1024),2) . " kB"; echo "</span></p>\n";
      }
    }
    
    if ((!empty($vyhledat)) and (substr_count($soubor, $vyhledat) > 0)) {
      if (substr_count($soubor, '.') > 0) {
      echo "<p class=\"soubor ui-selectee\" id=\"checkbox$o\">";
      ikona($soubor, $adres);
      echo "<a href=\""; echo ("$adres"); echo ($soubor);
      echo "\">"; echo ($soubor); echo"</a><input class=\"checkb\" type=\"checkbox\" name=\"checksoub[]\" value=\"$soubor\"> ";
      echo "<span class=\"velsoub\">" .  round(((filesize("$adres/$soubor"))/1024),2) . " kB"; echo "</span>";
      echo "</p>\n";
      }
      if (substr_count($soubor, '.') == 0) {
      echo "<p class=\"soubor ui-selectee\" id=\"checkbox$o\">";
      ikona($soubor, $adres);
      echo "<a href=\""; echo ("index.php?slozka=$slozka");
      echo ($soubor); echo "\">"; echo ($soubor); echo"</a><input class=\"checkb\" type=\"checkbox\" name=\"checksoub[]\" value=\"$soubor\"> ";
      echo "<span class=\"velsoub\">" . round(((dirsize("$adres/$soubor"))/1024),2) . " kB"; echo "</span></p>\n";
      }
    }
  }
  if ($soubor == "blank") {
  echo "<span class=\"blank\"></span>";
  }
}
echo "<input type=\"hidden\" id=\"pocetsoubsmaz\" value=\"$nummm\">\n
<input type=\"hidden\" id=\"cestasoubsmaz\" value=\"$slozka\">\n
<input type=\"hidden\" id=\"checksmaz\" value=\"1\">\n
<div class=\"full2\">\n
<input type=\"button\" id=\"butsmazsoub\" value=\"Smazat označené soubory či složky\">\n
</div>\n
</div>\n
<div id=\"preview\" class=\"full\">\n
</div>
<div class=\"full\">\n";

echo "<p>Počet souborů: $num</p>";

$velik=dirsize($adres);
$velik2=round(($velik/1048576),3); 
echo '<p>Celková velikost: '.$velik2.' MB</p>';

if (empty($slozka)) {
$zbyva = round(((3670016000 - $velik)/1048576),3);  
echo '<p>Volné místo: '.$zbyva.' MB</p>';
} 
echo "</div>";
echo "<script type=\"text/javascript\" src=\"ajax.js\"></script>";
?>