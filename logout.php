<?php
session_start();
//セッション変数を全て解除する
$_SESSION = array();
//セッションクッキーを削除する
if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time() - 60, '/');
}
//セッションIDを破棄する
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>ログアウト</title>
</head>
<body>
<p>ログアウトしました</p>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>

