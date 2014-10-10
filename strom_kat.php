<div class="moduletable_menu">
<h3>E-shop</h3>
  <div class="moduletable_content">
<?php
global $cat_id;
if (!empty(JRequest::getVar('category_id'))) {
$cat_id = addslashes(strip_tags(trim(JRequest::getVar('category_id'))));
} else $cat_id = 0;

function whatcat($child) {
global $cat_id;
global $path;
$db = JFactory::getDBO();

$query1 = $db->getQuery(true);
$query1
  ->select($db->quoteName(array('category_parent_id')))
  ->from($db->quoteName('#__vm_category_xref'))
  ->where($db->quoteName('category_child_id') . ' = '. $db->quote($child));

  if ($cat_id != 0) {
  $db->setQuery($query1);
  $row = $db->loadRowList();
  $pocet = count($row);
    if ($pocet > 0) {
    array_push($path, $row[0][0]);
      if (($row[0][0]) != 0) {
      whatcat($row[0][0]);    
      }
    }
  }
}
global $path;
$path = array($cat_id);
whatcat($cat_id);

global $pathr;
$pathr = array_reverse($path);

function menu($parent,$cnt) {
global $odkaz;
global $pathr;
global $db;

$db2 = JFactory::getDBO();
$query2 = $db2->getQuery(true);        
$query2
    ->select($db2->quoteName(array('b.category_parent_id', 'b.category_child_id', 'a.category_name')))
    ->from($db2->quoteName('#__vm_category', 'a'))
    ->join('INNER', $db2->quoteName('#__vm_category_xref', 'b') . ' ON (' . $db2->quoteName('b.category_child_id') . ' = ' . $db2->quoteName('a.category_id') . ')')
    ->where($db2->quoteName('a.category_publish') . ' = \'Y\'')
    ->where($db2->quoteName('b.category_parent_id') . ' = '. $db2->quote($parent));

if ($cnt > 0) {
$query2->order($db2->quoteName('a.category_name') . ' ASC');
}

$db2->setQuery($query2);
$row = $db2->loadRowList();
$pocet = count($row);

  if ($cnt == 0) {
  echo "<ul class=\"menu\">\n";
  }
  if ($cnt == 1) {
  echo "<ul class=\"menu-sub\">\n";
  }
  if ($cnt == 2) {
  echo "<ul class=\"menu-sub-sub\">\n";
  }
  if ($cnt > 2) {
  echo "<ul class=\"menu-sub-sub-sub\">\n";
  }

  for ($i=0;$i<$pocet;$i++) {
  $cnt2 = $cnt+1;  
    if (($row[$i][0]==$pathr[$cnt]) and ($row[$i][1]==$pathr[$cnt2])) {
    echo "<li><a class=\"active\" href=\"http://localhost/pneu/component/virtuemart/?page=shop.browse&category_id="
         . $row[$i][1] . "\">" . $row[$i][2] . "</a>\n";

    $cnt=$cnt+1;
    
    $db3 = JFactory::getDBO();    
    $query3 = $db3->getQuery(true);
    $query3
      ->select($db3->quoteName(array('b.category_parent_id')))
      ->from($db3->quoteName('#__vm_category', 'a'))
      ->join('INNER', $db3->quoteName('#__vm_category_xref', 'b')
            . ' ON (' . $db3->quoteName('b.category_child_id') . ' = ' . $db3->quoteName('a.category_id') . ')')
      ->where($db3->quoteName('a.category_publish') . ' = \'Y\'')
      ->where($db3->quoteName('b.category_parent_id') . ' = '. $db3->quote($row[$i][1]));


    $db3->setQuery($query3);
    $row2 = $db3->loadRowList();
    $pocet2 = count($row2);

      if ($pocet2 > 0) {
      menu($row[$i][1],$cnt);
      }
    } else echo "<li><a href=\"http://localhost/pneu/component/virtuemart/?page=shop.browse&category_id="
                . $row[$i][1] . "\">" . $row[$i][2] . "</a>\n";  
  echo "</li>\n";  
  }
echo "</ul>\n";
}

menu(0,0);
?>
  </div>
</div>