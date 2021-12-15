<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
    <title>評価実験2</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: https://miho.nkmr.io/f_review/login.php');
    exit();
}
$userId = $_SESSION['id'];
//echo "デバッグ用: 現在" . $userId . "さんがログイン中です。" . "</br></br>";

//最初の設定
$dsn = "mysql:host=localhost; dbname=xxxx; charset=utf8";
$username = "xxxx";
$password = "xxxx";

//接続確認
try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql2 = "SELECT ID, TaskID_1, TaskID_2  from Task2 WHERE ID NOT IN (SELECT taskId FROM Result2 WHERE userId = $userId) ";
$stmt2 = $dbh->query($sql2);
#$stmt2->bindValue(1, $userId, PDO::PARAM_INT); //パラメタ設定
$stmt2->execute();
$count = $stmt2->rowCount();

echo 67 - $count, ' / 66 枚目';

$sql = "SELECT ID, TaskID_1, TaskID_2  from Task2 WHERE ID NOT IN (SELECT taskId FROM Result2 WHERE userId = ?) ORDER BY RAND() LIMIT 1";
$stmt = $dbh->prepare($sql); //SQL文の設定
$stmt->bindValue(1, $userId, PDO::PARAM_INT); //パラメタ設定
$stmt->execute(); //SQLの実行
$result = $stmt->fetch();
if (!isset($result['ID'])) {
    header('Location: https://miho.nkmr.io/f_review/finish2.php');
    exit();
}
$taskId = $result['ID'];
$taskId_1 = $result['TaskID_1'];
$taskId_2 = $result['TaskID_2'];

//画像のパス指定
$imgurl_1 = 'https://miho.nkmr.io/f_review/images/' . $userId . '/' . $taskId_1 . '.jpg';
$imgurl_2 = 'https://miho.nkmr.io/f_review/images/' . $userId . '/' . $taskId_2 . '.jpg';

?>

<body>

    <br>
    <br>

    <!-- 画像を並列して並べる -->
    <div class="img-flex-2">
        <img src=<?php echo $imgurl_1; ?> alt="">
        <img src=<?php echo $imgurl_2; ?> alt="">
    </div>
    <br>
    <br>

    <!-- 評価フォーム -->
    <form action="resisterImpression2.php" method="post">
        <input type="hidden" name="taskId" value=<?php echo $taskId; ?>>
        <?php
        //評価値をシャッフルして表示
        $impressions = [
            ["Like_Dislike", "左の方が好ましい", "右の方が好ましい"],
            ["Easy_Hard", "左の方が塗りムラがわかりやすい", "右の方が塗りムラがわかりやすい"],
        ];

        function createQuestion($labelName, $minLabel, $maxLabel)
        {
            echo "<h4>「{$minLabel}」か「{$maxLabel}」かを評価してください";
            echo "<div class='radios'>";
            echo "<label><input type='radio' name={$labelName} value=1 required />{$minLabel}</label>";
            echo "<label><input type='radio' name={$labelName} value=2 />{$maxLabel}</label>";
            echo "</div>";
        }

        shuffle($impressions);

        foreach ($impressions as $row) {
            createQuestion($row[0], $row[1], $row[2]);
        }

        ?>
        <br>
        <input type="submit" value="評価する">
    </form>
    </div>


</body>

</html>