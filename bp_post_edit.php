<?php
session_start();
$error_arr = [];
//投稿IDを取得
if (!empty($_GET['post_id'])) {
	$post_id = htmlspecialchars($_GET['post_id'], ENT_QUOTES, 'utf-8');
}
//タイトルが入力されている場合、タイトルを取得
if (!empty($_GET['title'])) {
	$title = htmlspecialchars($_GET['title'], ENT_QUOTES, 'utf-8');
//タイトルが入力されていない場合
} else {
	$title = 'タイトルなし';
}
//本文が入力されていない場合
if (empty($_GET['content'])) {
	$error_arr[] = 'エラー : 本文を入力してください。';
//本文が入力されている場合、本文を取得
} else {
	$content = htmlspecialchars($_GET['content'], ENT_QUOTES, 'utf-8');
	try {
		require('db_connect.php');
		$dbh = connectDatabase();
		//タイトルと本文がDBに登録されているものと同じ内容かチェックするSQLを作成
		$content_check_sql = 'SELECT * FROM `posts` WHERE `id` = ? AND `title` = ? AND `content` = ?';
		$content_check_stm = $dbh->prepare($content_check_sql);
		$content_check_stm->bindValue(1, $post_id, PDO::PARAM_INT);
		$content_check_stm->bindValue(2, $title, PDO::PARAM_INT);
		$content_check_stm->bindValue(3, $content, PDO::PARAM_STR);
		$content_check_stm->execute();
		$content_check_res = $content_check_stm->fetch();
		//本文がDBに登録されている本文と同じ内容のとき
		if ($content_check_res) {
			$error_arr[] = 'エラー : 編集されていません。同じ内容です。';
		//本文が編集されたとき
		} else {
			//タイトルがないとき
			if (empty($title)) {
				$content_update_sql = 'UPDATE `posts` SET `content` = ? WHERE `id` = ?';
				$content_update_stm = $dbh->prepare($content_update_sql);
				$content_update_stm->bindValue(1, $content,  PDO::PARAM_STR);
				$content_update_stm->bindValue(2, $post_id, PDO::PARAM_INT);
			//タイトルがあるとき
			} else {
				$content_update_sql = 'UPDATE `posts` SET `title` = ?, `content` = ? WHERE `id` = ?';
				$content_update_stm = $dbh->prepare($content_update_sql);
				$content_update_stm->bindValue(1, $title,  PDO::PARAM_STR);
				$content_update_stm->bindValue(2, $content, PDO::PARAM_STR);
				$content_update_stm->bindValue(3, $post_id, PDO::PARAM_INT);
			}
			$content_update_stm->execute();
			$content_update_res = $content_update_stm->rowCount();
			//タイトルと本文がインサートされなかった場合
			if (!$content_update_res) {
				$error_arr[]  = 'エラー : 編集できませんでした。';
			}
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
<title>会員登録結果</title>
</head>
<body>
<!--エラーがない場合-->
<?php if (empty($error_arr)): ?>
<p>編集しました</p>
<a href="./new_post_form.php">新規投稿</a>
<!--エラーがある場合-->
<?php else: ?>
<?php foreach ($error_arr as $err_msg): ?>
<p><?php echo $err_msg; ?></p>
<?php endforeach; ?>
<p>編集画面へ<a href="./post_edit_form.php?title=<?php echo $title; ?>&content=<?php echo $content; ?>&post_id=<?php echo $post_id; ?>">戻る</a></p>
<?php endif; ?>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
