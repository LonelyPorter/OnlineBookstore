<?php
  function generateOrder() {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $orderNumber = '';

    for ($i = 0; $i < 2; $i++) {
      $orderNumber .= $characters[rand(0, 25)];
    }

    for ($i = 2; $i < 10; $i++) {
      $orderNumber .= $numbers[rand(0, 9)];
    }

    $orderNumber = str_shuffle($orderNumber);
    return $orderNumber;
  }

  $order = generateOrder();
  echo $order;
?>
