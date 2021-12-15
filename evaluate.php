<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name=”viewport” content=”width=device-width,initial-scale=1.0″>
    <title>評価実験１</title>
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
$sql2 = "SELECT ID from Task WHERE ID NOT IN (SELECT taskId FROM Result WHERE userId = $userId) ";
$stmt2 = $dbh->query($sql2);
#$stmt2->bindValue(1, $userId, PDO::PARAM_INT); //パラメタ設定
$stmt2->execute();
$count = $stmt2->rowCount();

echo 73 - $count, ' / 72 枚目';


$sql = "SELECT ID from Task WHERE ID NOT IN (SELECT taskId FROM Result WHERE userId = ?) ORDER BY RAND() LIMIT 1";
$stmt = $dbh->prepare($sql); //SQL文の設定
$stmt->bindValue(1, $userId, PDO::PARAM_INT); //パラメタ設定
$stmt->execute(); //SQLの実行
$result = $stmt->fetch();
if (!isset($result['ID'])) {
    header('Location: https://miho.nkmr.io/f_review/finish.php');
    exit();
}
$taskId = $result['ID'];

//画像のパス指定
$imgurl_chg = 'https://miho.nkmr.io/f_review/images/' . $userId . '/' . $taskId . '.jpg';
?>

<body>
    <br>
    <br>
    <div class="image_c"><img src=<?php echo $imgurl_chg; ?> class="image_size_m"></div>
    <!-- 評価フォーム -->
    <br>


    <div div style="width:auto;height:250px;overflow:auto;">

        <form action="resisterImpression.php" method="post">
            <input type="hidden" name="taskId" value=<?php echo $taskId; ?>>
            <?php
            //評価値をシャッフルして表示

            //$impressions = [
            //    ["Vivid_Dully", "くすんでいる", "くすんでいない"],
            //    ["Bright_Dark", "明るい", "暗い"],
            //    ["Soft_Hard", "やわらかい", "かたい"],
            //    ["Warm_Cold", "あたたかい", "つめたい"],
            //    ["Active_Inactive", "積極的な", "消極的な"],
            //    ["Silent_Noisy", "静かな", "うるさい"],
            //    ["Happy_Bad", "楽しい", "苦しい"],
            //    ["Elegant_Naughty", "派手な", "地味な"],
            //    ["Fun_Bored", "面白い", "つまらない"],
            //    ["Powerful_Weak", "たくましい", "弱弱しい"],
            //    ["Dynamic_Static", "動的", "静的"],
            //    ["Stable_Unstable", "安定した", "不安定な"]
            //];

            $impressions = [
                ['Clear_Dully', '透明感がある', 'くすんでいる'],
                ['Bright_Dark', '明るい', '暗い'],
                ['Soft_Hard', 'やわらかい', 'かたい'],
                ['Warm_Cold', 'あたたかい', 'つめたい'],
                ['Active_Inactive', '積極的な', '消極的な'],
                ['Silent_Noisy', '静かな', 'うるさい'],
                ['Elegant_Naughty', '派手な', '地味な'],
                ['Fun_Bored', '面白い', 'つまらない'],
                ['Powerful_Weak', 'たくましい', '弱弱しい'],
                ['Stable_Unstable', '安定した', '不安定な'],
                ['Urban_Countrylike', '都会っぽい', '田舎っぽい'],
                ['Young_Old', '若々しい', '年老いた'],
                ['Glossy_Matte', '光沢感がある', '光沢感がない']
            ];
            function createQuestion($labelName, $minLabel, $maxLabel)
            {
                echo "<h4>「{$minLabel}」か「{$maxLabel}」かを評価してください";
                echo "<div class='radios'>";
                echo "<label><input type='radio' name={$labelName} value=1 required />非常に{$minLabel}</label>";
                echo "<label><input type='radio' name={$labelName} value=2 />とても{$minLabel}</label>";
                echo "<label><input type='radio' name={$labelName} value=3 />少し{$minLabel}</label>";
                echo "<label><input type='radio' name={$labelName} value=4 />どちらでもない</label>";
                echo "<label><input type='radio' name={$labelName} value=5 />少し{$maxLabel}</label>";
                echo "<label><input type='radio' name={$labelName} value=6 />とても{$maxLabel}</label>";
                echo "<label><input type='radio' name={$labelName} value=7 />非常に{$maxLabel}</label>";
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