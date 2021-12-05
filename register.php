<?php
//名前が入力されていない場合
if (empty($_POST['name'])) {
	$error_arr[] = 'エラー : 名前を入力してください。';
//名前が入力されている場合
} else {
	$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'utf-8');
}
//メールアドレスが入力されていない場合
if (empty($_POST['mail'])) {
	$error_arr[] = 'エラー : メールアドレスを入力してください。';
//メールアドレスが入力されている場合
} else {
	$mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'utf-8');
}
//パスワードが正しく入力されていない場合
if (!preg_match('/^[0-9A-Za-z]+$/', $_POST['pass'])) {
	$error_arr[] = 'エラー : パスワードの値が不正です。';
//パスワードが正しく入力されている場合
} else {
	$pass = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'utf-8');
}
//全て入力されているとき
if (empty($error_arr)) {
	try {
		require('db_connect.php');
		$dbh = connectDatabase();
		$mail_check_sql = 'SELECT * FROM `members` WHERE `mail` = ?';
		$mail_check_stm = $dbh->prepare($mail_check_sql);
		$mail_check_stm->bindValue(1, $mail, PDO::PARAM_STR);
		$mail_check_stm->execute();
		$mail_check_res = $mail_check_stm->fetch(PDO::FETCH_ASSOC);
		//メールアドレスが既に存在している場合
		if ($mail_check_res) {
			$error_arr[] = 'エラー : そのメールアドレスは既に存在しています。';
		//メールアドレスが存在していない場合
		} else {
			$member_register_sql = 'INSERT INTO `members` (`name`, `pass`, `mail`) VALUES (?, ?, ?)';
			$member_register_stm = $dbh->prepare($member_register_sql);
			$member_register_stm->bindValue(1, $name, PDO::PARAM_STR);
			$member_register_stm->bindValue(2, $pass, PDO::PARAM_STR);
			$member_register_stm->bindValue(3, $mail, PDO::PARAM_STR);
			$member_register_stm->execute();
			$member_register_res = $member_register_stm->rowCount();
			//会員登録がされなかった場合
			if (!$member_register_res) {
				$error_arr[]  = 'エラー : 会員登録できませんでした。';
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
<p>会員登録しました</p>
<a href="./new_post_form.php">新規投稿</a>
<!--エラーがある場合-->
<?php else: ?>
<?php foreach ($error_arr as $err_msg): ?>
<p><?php echo $err_msg; ?></p>
<?php endforeach; ?>
<p>会員登録画面へ<a href="./register_form.php">戻る</a></p>
<?php endif; ?>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
