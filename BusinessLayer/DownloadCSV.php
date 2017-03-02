<?php
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="portfolio.csv"');
  $fp = fopen("/afs/cad/u/k/n/kn259/public_html/BusinessLayer/Download/portfolio.csv", "r");
  fpassthru($fp);
  fclose($fp);
?>