<?php
session_start();
date_default_timezone_set('Asia/Tashkent');
require '../models/connection.php';

global $pdo;

$botToken = '8099038519:AAEv0wOiEy5MJbQxX0h7er8ZVXOIGQyqcLQ';
$chatId = '-1002261167789';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $product = $_POST['product']; 
    $site = $_SERVER['HTTP_HOST'];
    $created_at = date('Y-m-d H:i:s');

    if (empty($name) || empty($contact) || empty($product)) {
        if (empty($name)) {
            header("Location: index.php");
            exit();
        }
        if (empty($contact)) {
            header("Location: index.php");
            exit();
        }
        if (empty($product)) {
            header("Location: index.php");
            exit();
        }
    } else {
        try {
            $sql = "INSERT INTO `leads` (name, contact, product, created_at, site) VALUES (:name, :contact, :product, :created_at, :site)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':contact' => $contact,
                ':product' => $product, 
                ':created_at' => $created_at,
                ':site' => $site
            ]);
        } catch (PDOException $e) {
            echo "Error saving data: " . $e->getMessage();
        }

        $message = "Name: " . $name . "\n\nContact: " . $contact . "\n\nProduct: " . $product . "\n\nSayt: " . $_SERVER['HTTP_HOST'];
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);
        $response = file_get_contents($url);

        if ($response) {
            $_SESSION['message'] = "Sizning murojaatingiz qabul qilindi. Tez orada mutaxassislarimiz siz bilan bog‘lanishadi.";
        } else {
            $_SESSION['message'] = "Xabar yuborishda xato.";
            header("Location: index.php");
            exit();
        }
    }
} else {
    echo "Noto'g'ri so'rov.";
}
?>

<?php
session_start();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';

unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMvR6E5i4eI5Qj3I5d3RkfdQXh5mNRpJ9c" crossorigin="anonymous">
    <title>Zar Zamin</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #129F54;
        }

        .container {
            text-align: center;
            font-size: 24px;
            color: white;
        }

        .icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
            border: 2px solid white;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>

<div class="flex flex-col items-center">
    <div class="container">
        Raqamingiz qoldirildi. Tez orada mutaxxassislarimiz siz bilan bog'lanadi!
    </div>
    <div class="container">
        <a href="https://www.instagram.com/manaku.uz?igsh=YXF2ZW83aGt5dnZ3&utm_source=qr" target="_blank">
            <button class="mt-4 flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold rounded-full shadow-lg transform transition-transform duration-200 hover:scale-105 hover:shadow-xl">
                <img src="images/logo2.jpg" alt="icon" class="icon"> 
                <span class="flex items-center">
                        <i class="fab fa-instagram mr-2"></i>
                        Instagram sahifasiga o'tish
                    </span>
            </button>
        </a>
    </div>
</div>

</body>
</html>

