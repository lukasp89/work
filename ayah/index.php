<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Are You A Human</title>
</head>
<body>
  <form method="post" action="">
    <p>Komentář: <input type="text" name="name" /></p>
    <?php echo $ayah->getPublisherHTML(); ?>
    <input type="Submit" name="submit_name" value="Odeslat">
  </form>
<?php
require_once("ayah.php");
$ayah = new AYAH();

if (array_key_exists('submit_name', $_POST)) {
  $score = $ayah->scoreResult();

  if ($score) {
    echo "Jste člověk!";
  }
  else {
    echo "Ověření bylo neúspěšné. Prosím zkuste to znovu.";
  }
}
?>
</body>
</html>