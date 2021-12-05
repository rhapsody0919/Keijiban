<?php
session_start();
if (empty($_GET['post_id'])) {
	$error_arr[] = 'エラー : 投稿IDが取得できませんでした。';
} else {
	$post_id = htmlspecialchars($_GET['post_id'], ENT_QUOTES, 'utf-8');
}
try {
	require('db_connect.php');
	$dbh = connectDatabase();
	$get_post_sql = 'SELECT `title`,`content`
	   				FROM `posts`
					WHERE `id` = ' . $post_id;
	$get_post_stm = $dbh->query($get_post_sql);
	$get_post_res = $get_post_stm->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$exception_msg = 'ERROR:' . $e->getMessage();
	exit($exception_msg);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>投稿編集</title>
</head>
<body>
<?php if (empty($error_arr)): ?>
<h1>編集</h1>
<form action="./post_edit.php" method="POST">
<label>タイトル: <input type="text" name="title" value="<?php echo $get_post_res['title']; ?>"></label>
<p>本文: </p>
<textarea name="content" rows="6" cols="80"><?php echo $get_post_res['content']; ?></textarea>
<input type="submit" value="編集">
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
</form>
<?php else: ?>
<?php foreach ($error_arr as $err_msg): ?>
<p><?php echo $err_msg; ?></p>
<?php endforeach; ?>
<?php endif; ?>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
