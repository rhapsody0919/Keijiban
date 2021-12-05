<?php
session_start();
//投稿IDを取得
if (empty($_POST['post_id'])) {
	$error_arr[] = 'エラー : 投稿IDが取得できませんでした。';
} else {
	$post_id = htmlspecialchars($_POST['post_id'], ENT_QUOTES, 'utf-8');
}
//タイトルを取得
if (empty($_POST['title'])) {
	$title = 'タイトルなし';
} else {
	$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'utf-8');
}
//本文が入力されていない場合
if (empty($_POST['content'])) {
	$error_arr[] = 'エラー : 本文を入力してください。';
//本文が入力されている場合、本文を取得
} else {
	$content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'utf-8');
}
if (empty($error_arr)) {
	try {
		require('db_connect.php');
		$dbh = connectDatabase();
		//タイトルと本文がDBに登録されているものと同じ内容かチェックするSQLを作成
		$content_check_sql = 'SELECT `title`, `content`, `member_id` FROM `posts` WHERE `id` = ?';
		$content_check_stm = $dbh->prepare($content_check_sql);
		$content_check_stm->bindValue(1, $post_id, PDO::PARAM_INT);
		$content_check_stm->execute();
		$content_check_res = $content_check_stm->fetch(PDO::FETCH_ASSOC);
		//投稿者の会員IDがセッション情報のIDと同一でない場合
		if ($content_check_res['member_id'] !== $_SESSION['member']['user_id']) {
			$error_arr[] = 'エラー : 投稿者以外編集できません';
		//本文がDBに登録されている本文と同じ内容の場合
		} elseif ($content_check_res['title'] === $title && $content_check_res['content'] === $content) {
			$error_arr[] = 'エラー : 編集されていません。同じ内容です。';
		//編集された場合、タイトルと本文をアップデート
		} else {
			$content_update_sql = 'UPDATE `posts` SET `title` = ?, `content` = ? WHERE `id` = ?';
			$content_update_stm = $dbh->prepare($content_update_sql);
			$content_update_stm->bindValue(1, $title,  PDO::PARAM_STR);
			$content_update_stm->bindValue(2, $content, PDO::PARAM_STR);
			$content_update_stm->bindValue(3, $post_id, PDO::PARAM_INT);
			$content_update_stm->execute();
			$content_update_res = $content_update_stm->rowCount();
			//タイトルと本文がアップデートされなかった場合
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
<?php endif; ?>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
