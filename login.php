<meta name=”viewport” content=”width=device-width,initial-scale=1.0″>

<?php
session_start();
$msg = "ユーザー名とパスワードを入れてください";
if (isset($_POST['name']) and isset($_POST['pass'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    //最初の設定
    $dsn = "mysql:host=localhost; dbname=xxxx; charset=utf8";
    $username = "xxxx";
    $password = "xxxx";

    //接続確認
    try {
        $dbh = new PDO($dsn, $username, $password);
        $msg = $name;
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }

    $sql = "SELECT ID FROM User WHERE Name = ? AND Password = ?";
    $stmt = $dbh->prepare($sql); //SQL文の設定
    $stmt->bindValue(1, $name, PDO::PARAM_STR); //パラメタ設定
    $stmt->bindValue(2, $pass, PDO::PARAM_INT); //パラメタ設定
    $stmt->execute(); //SQLの実行
    $member = $stmt->fetch();

    //指定したハッシュがパスワードにマッチしているかチェック
    if (isset($member["ID"])) {
        //DBのユーザー情報をセッションに保存
        $_SESSION['id'] = $member['ID'];
        header('Location: https://miho.nkmr.io/f_review/index.php');
        //header('Location: https://miho.nkmr.io/f_review/evaluate.php');
        //$msg = "現在" . $_SESSION['id'] . "さんがログイン中です。";
        ///$link = '<a href="evaluate.php">実験開始</a>';
    } else {
        $msg = 'ユーザー名もしくはパスワードが間違っています。';
    }
}
?>

<h1>ログインページ</h1>
<h1><?php echo $msg; ?></h1>
<form action="login.php" method="post">
    <div>
        <label>ユーザー：<label>
                <input type="text" name="name" required>
    </div>
    <div>
        <label>パスワード：<label>
                <input type="password" name="pass" required>
    </div>
    <input type="submit" value="ログイン">
</form>