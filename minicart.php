<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Minicart příklad</title>
  <style type="text/css">
  body {background-color:#E9EAED; font-family:tahoma;}
  h1 {text-align: center;font-size: 20pt;}  
  div.str {padding: 10px;}
  div.wrap {width:970px;background-color:#FFFFFF;position:relative;overflow:hidden;margin:3px auto;border-radius:3px;}
  div.polozka {width:218px;margin:5px 0 5px 10px;float:left;border:1px solid grey;border-radius:3px;padding:5px;background-color:#FCF6DE}
  fieldset {border: none;padding: 0;}
  *.zobrkos {float:right;}
  </style>
</head>
<body>
<div class="wrap">
<h1>Vše za 1000,- Kč!</h1>
<?php
class minicart {
    
  private function stranka() {
    if(!empty($_GET["p"])) {
    return $_GET["p"]+1;
    } else return 1;
  }
  
  public function strankovani() {
  echo "<span>Strana: </span>\n";
  $i = 0;
    while ($i < 3) {
      if (($i*10)==($this->stranka()-1)) {
      echo "<strong>\n";
      }
    echo "<a href=\"?p=" . ($i*10) . "\">" . ($i+1) . "</a>\n";
      if (($i*10)==($this->stranka()-1)) {
      echo "</strong>\n";
      }
    $i++;
    }
  }
  
  private function polozka($cislo) {
  echo "  <div class=\"polozka\">
    <p class=\"nazpol\">Položka $cislo</p>
    <img class=\"imgpol\" alt=\"$cislo\" src=\"http://www.pneu-krejcarek.cz/components/com_virtuemart/themes/default/images/noimage.gif\">
    <p class=\"Cena\">1000 Kč</p>
    <form method=\"post\" action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" target=\"paypal\">
      <fieldset>
        <input type=\"hidden\" value=\"_cart\" name=\"cmd\">
        <input type=\"hidden\" value=\"1\" name=\"add\">
        <input type=\"hidden\" value=\"example@minicartjs.com\" name=\"business\">
        <input type=\"hidden\" value=\"Položka $cislo\" name=\"item_name\">
        <input type=\"hidden\" value=\"1000\" name=\"amount\">
        <input type=\"hidden\" value=\"CZK\" name=\"currency_code\">
        <input type=\"hidden\" value=\"http://www.minicartjs.com/?success\" name=\"return\">
        <input type=\"hidden\" value=\"http://www.minicartjs.com/?cancel\" name=\"cancel_return\">   
        <input type=\"submit\" class=\"button\" value=\"Přidat do košíku\" name=\"submit\">
      </fieldset>
    </form>
    </div>  
  ";
  }
                            
  public function vicepol() {
  $j = $this->stranka();
  $k = $j;
    while ($j < $k+10) {
    //echo $j;
    echo $this->polozka($j);
    $j++;
    }
  
  }

}

$polozka = new minicart;
?>
<div class="str">
<?php
$polozka->strankovani();
?>
  
  <form class="zobrkos" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
      <fieldset>
          <input type="hidden" value="example@minicartjs.com" name="business">
          <input type="hidden" value="_cart" name="cmd">
          <input type="hidden" value="1" name="display">
          <input type="submit" class="button" value="Zobrazit košík" name="submit">
      </fieldset>
  </form>
  <input class="zobrkos" type="button" onclick="paypal.minicart.reset();paypal.minicart.view.show()" value="Vyprázdnit košík">
</div>
<?php
$polozka->vicepol();
?>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/minicart/3.0.5/minicart.min.js"></script>
<script>
paypal.minicart.render({
  strings: {
      button: 'Zaplatit přes <img src="http://cdnjs.cloudflare.com/ajax/libs/minicart/3.0.1/paypal_65x18.png" width="65" height="18" alt="PayPal" />',
      buttonAlt: "Zaplatit přes PayPal:",
      subtotal: "Celkem:",
      empty:"Váš nákupní košík je prázdný",
      discount:"Sleva:",
      processing:"Zpracovávám..."
  }
});
</script>
</body>
</html>