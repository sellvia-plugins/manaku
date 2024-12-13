<?php

require_once "../../models/connection.php";
global $pdo;

$site = isset($_GET['site']) ? $_GET['site'] : null;

try {
    if ($site) {
        $stmt = $pdo->prepare("SELECT name, contact, product, created_at FROM leads WHERE site = :site");
        $stmt->execute(['site' => $site]);
        $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $leads = [];
    }
} catch (PDOException $e) {
    $leads = [];
    echo "Error: " . $e->getMessage();
}

function formatContact($contact) {
    if (strlen($contact) < 4) {
        return $contact;
    }

    $start = substr($contact, 0, 2);
    $end = substr($contact, -2);
    $masked = str_repeat('*', strlen($contact) - 4);

    return $start . $masked . $end;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads Ma'lumotlari</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 40vh;
            margin: 0;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        caption {
            font-size: 24px;
            margin: 15px 0;
            color: #333;
        }
    </style>
</head>
<body>

<table>
    <caption>Leads Ma'lumotlari</caption>
    <thead>
    <tr>
        <th>N_o</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Product</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody id="leads-table">
    <?php
        $i = 1;
        foreach ($leads as $lead): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($lead['name']) ?></td>
                <td><?= htmlspecialchars(formatContact($lead['contact'])) ?></td>
                <td><?= htmlspecialchars($lead['product']) ?></td>
                <td><?= htmlspecialchars($lead['created_at']) ?></td>
            </tr>
    <?php endforeach; ?>
    <?php if (empty($leads)): ?>
            <tr>
                <td colspan="4">Ma'lumot topilmadi.</td>
            </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
