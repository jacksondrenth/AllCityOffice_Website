<?php

require_once("db.php");
// checks if the POST variable is set
if (isset($_POST["ajaxID"])) {

  // sets the schema PHP variable
  $ajaxID = $_POST["ajaxID"];

  // gets the information of the employee
  $query =
  "SELECT *
  FROM product
  WHERE description = ?";
  $stmt = $conn->prepare($query);

  // executes the query
  $stmt->execute([$ajaxID]);

  // gets the first (and only) row of the results
  $row = $stmt->fetch();

  echo
        "<table class='table table-bordered'>
        <tr>
          <th>Part Number</th>
          <td>{$row["part_number"]}</td>
        </tr>
        <tr>
          <th>Manufacturer Sku</th>
          <td>{$row["manufacturer_sku"]}</td>
        </tr>
        <tr>
          <th>Back Ordered?</th>
          <td>{$row["back_ordered"]}</td>
        </tr>
        <tr>
          <th>Sale Price</th>
          <td>\${$row["sale_price"]}</td>
        </tr>
        <tr>
          <th>Type</th>
          <td>{$row["type"]}</td>
        </tr>
      </table>";
}
?>