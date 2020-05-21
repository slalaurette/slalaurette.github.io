<?php
// file: simple_interest.php
if (is_numeric($_POST['p']) && is_numeric($_POST['r']) && is_numeric($_POST['n'])) {
  $p = $_POST['p'];
  $r = $_POST['r'];
  $n = $_POST['n'];
  $result = $p * $r/100 * $n;
  echo $result;
} else {
  echo 'Input error';  
}
?>