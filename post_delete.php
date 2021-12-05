<?php
session_start();
if (empty($_GET['post_id'])) {
	$error_arr[] = 'エラー : 投稿IDを取得できませんでした。';
//投稿IDを取得
} else {
	$post_id = htmlspecialchars($_GET['post_id'], ENT_QUOTES, 'utf-8');
	try {
		require('db_connect.php');
		$dbh = connectDatabase();
		$get_member_id_sql = 'SELECT `member_id` FROM `posts` WHERE `id` = ?';
		$get_member_id__stm = $dbh->prepare($get_member_id_sql);
		$get_member_id__stm->bindValue(1, $post_id, PDO::PARAM_INT);
		$get_member_id__stm->execute();
		$get_member_id__res = $get_member_id__stm->fetch(PDO::FETCH_ASSOC);
		//投稿者の会員IDがセッション情報のIDと同一でない場合
		if ($get_member_id__res['member_id'] !== $_SESSION['member']['user_id']) {
			$error_arr[] = 'エラー : 投稿者以外編集できません';
		//投稿者の会員IDがセッション情報のIDと同一である場合
		} else {
			$deleted_flag_update_sql = 'UPDATE `posts` SET `deleted_flag` = TRUE WHERE `id` = ?';
			$deleted_flag_update_stm = $dbh->prepare($deleted_flag_update_sql);
			$deleted_flag_update_stm->bindValue(1, $post_id, PDO::PARAM_INT);
			$deleted_flag_update_stm->execute();
			$deleted_flag_update_res = $deleted_flag_update_stm->rowCount();
			//タイトルと本文がインサートされなかった場合
			if (!$deleted_flag_update_res) {
				$error_arr[]  = 'エラー : 削除できませんでした。';
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
<title>投稿削除結果</title>
</head>
<body>
<!--エラーがない場合-->
<?php if (empty($error_arr)): ?>
<p>削除しました</p>
<!--新規投稿-->
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
