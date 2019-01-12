<?php
  if (!isset($_GET['invoice'])) {
    header('Location: index.php');
    exit();
  }

  $pdo = new PDO('sqlite:chinook.db');
  $statement = $pdo->prepare('
    select artists.Name as ArtistName, tracks.Name as TrackName, Quantity, invoice_items.UnitPrice
    from invoice_items
    inner join tracks
    on invoice_items.TrackId = tracks.TrackId
    inner join albums
    on tracks.AlbumId = albums.AlbumId
    inner join artists
    on artists.ArtistId = albums.ArtistId
    where InvoiceId = ?
  ');
  $statement->bindParam(1, $_GET['invoice']);
  $statement->execute();
  $invoiceItems = $statement->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Invoice Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
  </head>
  <body>
    <table class="table">
      <tr>
        <th>Track</th>
        <th>Quantity</th>
        <th>Price</th>
      </tr>
      <?php foreach($invoiceItems as $invoiceItem) : ?>
        <tr>
          <td>
            <?php echo $invoiceItem->TrackName ?>
            by <?php echo $invoiceItem->ArtistName ?>
          </td>
          <td><?php echo $invoiceItem->Quantity ?></td>
          <td><?php echo $invoiceItem->UnitPrice ?></td>
        </tr>
      <?php endforeach ?>
    </table>
  </body>
</html>
