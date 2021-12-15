<meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
//登録する
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: https://miho.nkmr.io/f_review/index.php');
    exit();
}

$userId = $_SESSION['id'];

if (!isset($_POST['taskId'])) {
    header('Location: https://miho.nkmr.io/f_review/evaluate.php');
    exit();
}

//最初の設定
$dsn = "mysql:host=localhost; dbname=XXXX; charset=utf8";
$username = "xxxx";
$password = "xxxx";

//接続確認
try {
    $dbh = new PDO($dsn, $username, $password);
    //登録する
    //$labels = ["Vivid_Dully", "Bright_Dark", "Soft_Hard", "Warm_Cold", "Active_Inactive", "Silent_Noisy", "Happy_Bad", "Elegant_Naughty", "Fun_Bored", "Powerful_Weak", "Dynamic_Static", "Stable_Unstable"];

    $labels = [
        'Clear_Dully', 'Bright_Dark', 'Soft_Hard', 'Warm_Cold', 'Active_Inactive', 'Silent_Noisy', 'Elegant_Naughty',
        'Fun_Bored', 'Powerful_Weak', 'Stable_Unstable', 'Urban_Countrylike', 'Young_Old', 'Glossy_Matte'
    ];

    $sql = "INSERT INTO Result (UserID, TaskID, Clear_Dully, Bright_Dark, Soft_Hard, Warm_Cold, Active_Inactive, Silent_Noisy, Elegant_Naughty,Fun_Bored, Powerful_Weak, Stable_Unstable, Urban_Countrylike, Young_Old, Glossy_Matte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)";
    $stmt = $dbh->prepare($sql); //SQL文の設定

    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $_POST['taskId'], PDO::PARAM_INT);
    for ($i = 0; $i < 13; $i++) {
        $stmt->bindValue($i + 3, $_POST[$labels[$i]], PDO::PARAM_INT);
        echo $_POST[$labels[$i]];
    }

    $stmt->execute(); //SQLの実行


} catch (PDOException $e) {
    $msg = $e->getMessage();
} finally {
    // DB接続を閉じる
    $pdo = null;
    header('Location: https://miho.nkmr.io/f_review/evaluate.php');
    exit();
}

?>