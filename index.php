<?php
  include('vite.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Vite Asset Rewrite Test 1</title>

    <?= vite('asset-src/main.js'); ?>
  </head>
  <body>
    <h2>This works with dev but breaks with build:</h2>

    <div class="test"></div>
  </body>
</html>
