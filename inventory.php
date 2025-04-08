<?php include 'includes/db.php';

$search = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? 'all';
$sort = $_GET['sort'] ?? 'all';

$sql = "SELECT 
    si.seed_name,
    sv.variety_name,
    sc.category_name,
    SUM(sii.quantity) AS total_quantity,
    sv.price
FROM seedling_inventory sii
JOIN seedling_variety sv ON sii.seedling_variety_id = sv.id
JOIN seedling_info si ON sv.seed_id = si.id
JOIN seedling_category sc ON si.category_id = sc.id
WHERE 1";

if (!empty($search)) {
    $safeSearch = $conn->real_escape_string($search);
    $sql .= " AND si.seed_name LIKE '%$safeSearch%'";
}

if ($categoryFilter !== 'all') {
    $safeCat = $conn->real_escape_string($categoryFilter);
    $sql .= " AND sc.category_name = '$safeCat'";
}

$sql .= " GROUP BY sv.id, si.seed_name, sv.variety_name, sc.category_name, sv.price";

if ($sort === 'low') {
    $sql .= " HAVING total_quantity <= 15 ORDER BY total_quantity ASC";
} else {
    $sql .= " ORDER BY si.seed_name ASC";
}

$result = $conn->query($sql);


if (!$result) {
    die("Error executing query: " . $conn->error);
}

$inventory = $result->fetch_all(MYSQLI_ASSOC);
?>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Seed Name</th>
      <th>Variety</th>
      <th>Category</th>
      <th>Total Quantity</th>
      <th>Price</th>
      <th>Total Value</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($inventory)): ?>
      <tr><td colspan="6" class="text-center">No records found.</td></tr>
    <?php else: ?>
      <?php foreach ($inventory as $item): ?>
        <?php 
          $low_stock = $item['total_quantity'] <= 6;
          $row_class = $low_stock ? 'table-danger' : '';
        ?>
        <tr class="<?= $row_class ?>">
          <td><?= htmlspecialchars($item['seed_name']) ?></td>
          <td><?= htmlspecialchars($item['variety_name']) ?></td>
          <td><?= htmlspecialchars($item['category_name']) ?></td>
          <td>
            <?= $item['total_quantity'] ?>
            <?php if ($low_stock): ?>
              <span class="badge bg-danger ms-2">Low</span>
            <?php endif; ?>
          </td>
          <td>&#8369;<?= number_format($item['price'], 2) ?></td>
          <td>&#8369;<?= number_format($item['total_quantity'] * $item['price'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
