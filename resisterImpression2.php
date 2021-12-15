<meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
//登録する
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: https://miho.nkmr.io/f_review/login.php');
    exit();
}

$userId = $_SESSION['id'];

if (!isset($_POST['taskId'])) {
    header('Location: https://miho.nkmr.io/f_review/evaluate2.php');
    exit();
}

//最初の設定
$dsn = "mysql:host=localhost; dbname=xxxx; charset=utf8";
$username = "xxxx";
$password = "xxxx";

//接続確認
try {
    $dbh = new PDO($dsn, $username, $password);
    //登録する
    $labels = ["Like_Dislike", "Easy_Hard"];

    $sql = "INSERT INTO Result2 (UserID, TaskID, Like_Dislike, Easy_Hard) VALUES(?, ?, ?, ?)";
    $stmt = $dbh->prepare($sql); //SQL文の設定

    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $_POST['taskId'], PDO::PARAM_INT);
    for ($i = 0; $i < 2; $i++) {
        $stmt->bindValue($i + 3, $_POST[$labels[$i]], PDO::PARAM_INT);
        echo $_POST[$labels[$i]];
    }

    $stmt->execute(); //SQLの実行

} catch (PDOException $e) {
    $msg = $e->getMessage();
} finally {
    // DB接続を閉じる
    $pdo = null;
    header('Location: https://miho.nkmr.io/f_review/evaluate2.php');
    exit();
}

?>