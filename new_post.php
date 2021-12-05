<?php
session_start();
//タイトルを取得
if (!empty($_POST['title'])) {
	$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'utf-8');
}
//本文が入力されていない場合
if (empty($_POST['content'])) {
	$error_arr[] = 'エラー : 本文を入力してください。';
//本文が入力されている場合
} else {
	$content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'utf-8');
}
//本文が入力されている場合
if (empty($error_arr)) {
	try {
		require('db_connect.php');
		$dbh = connectDatabase();
		//タイトルがない場合
		if (empty($title)) {
			$content_insert_sql = 'INSERT INTO `posts` (`member_id`, `content`) VALUES (?, ?)';
			$content_insert_stm = $dbh->prepare($content_insert_sql);
			$content_insert_stm->bindValue(1, $_SESSION['member']['user_id'], PDO::PARAM_INT);
			$content_insert_stm->bindValue(2, $content, PDO::PARAM_STR);
		//タイトルがある場合
		} else {
			$content_insert_sql = 'INSERT INTO `posts` (`member_id`, `title`, `content`) VALUES (?, ?, ?)';
			$content_insert_stm = $dbh->prepare($content_insert_sql);
			$content_insert_stm->bindValue(1, $_SESSION['member']['user_id'], PDO::PARAM_INT);
			$content_insert_stm->bindValue(2, $title, PDO::PARAM_STR);
			$content_insert_stm->bindValue(3, $content, PDO::PARAM_STR);
		}
		$content_insert_stm->execute();
		$content_insert_res = $content_insert_stm->rowCount();
		//タイトルと本文がインサートされなかった場合
		if (!$content_insert_res) {
			$error_arr[]  = 'エラー : 投稿できませんでした。';
		}
	} catch (PDOException $e) {
		$exception_msg = 'ERROR:' . $e->getMessage();
		exit($exception_msg);
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>投稿結果</title>
</head>
<body>
<!--エラーがない場合-->
<?php if (empty($error_arr)): ?>
<p>投稿しました</p>
<a href="./new_post_form.php">新規投稿</a>
<!--エラーがある場合-->
<?php else: ?>
<?php foreach ($error_arr as $err_msg): ?>
<p><?php echo $err_msg; ?></p>
<?php endforeach; ?>
<p>新規投稿画面へ<a href="./new_post_form.php">戻る</a></p>
<?php endif; ?>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
