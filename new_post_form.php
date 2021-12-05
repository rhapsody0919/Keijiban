<?php
session_start();
//セッション情報がない場合
if (!isset($_SESSION['member'])) {
	header('Location: ./register_form.php');
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>新規投稿画面</title>
</head>
<body>
<h1>新規投稿</h1>
<p>投稿者名: <?php echo $_SESSION['member']['user_name']; ?></p>
<form action="./new_post.php" method="POST">
<label>タイトル: <input type="text" name="title"></label>
<p>本文: </p>
<textarea name="content" rows="4" cols="40"></textarea>
<input type="submit" value="登録">
<input type="reset" value="リセット">
</form>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
