<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 16px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}
</style>
</head>
<body>

<h2>BUY PRICE REPORT</h2>


<table style="width:100%">
  <tr>
    <th>S/No</th>
    <th>PRODUCT NAME</th>
    <th>BUY PRICE</th>
  </tr>
  <?php $rowId = 1; ?>
  <?php foreach ($products as $product): ?>
  <tr>
    <td> <?= $rowId < 10 ? "0" . $rowId++ : $rowId++ ?></td>
    <td><?= $product->name ?></td>
    <td><?= number_format($product->buy_price ) ?></td>
  </tr>
  <?php endforeach; ?>
 
 <!-- <tr>
   <th style="border: none"></th>
   <th style="border: none"></th>
   <th style="border: none;font-size:12px;">Total Buy Price:</th>
   <th style="border: none;font-size:12px;"></th>
   <th style="border: none"></th>
 </tr> -->
</table>

</body>
</html>
