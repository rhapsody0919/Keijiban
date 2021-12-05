<?php
session_start();
//メールアドレスが入力されていない場合
if (empty($_POST['mail'])) {
	$error_arr[] = 'メールアドレスを入力してください。';
//メールアドレスが入力されている場合
} else {
	$mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'utf-8');
}
//パスワードが入力されていない場合
if (empty($_POST['pass'])) {
	$error_arr[] = 'パスワードを入力してください。';
//パスワードが正しく入力されていない場合
} elseif (!preg_match('/^[0-9A-Za-z]+$/', $_POST['pass'])) {
	$error_arr[] = 'エラー : パスワードの値が不正です。';
//パスワードが正しく入力されている場合
} else {
	$pass = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'utf-8');
}
//全て正しく入力されているとき
if (empty($error_arr)) {
	try {
		require('db_connect.php');
		$dbh = connectDatabase();
		$member_check_sql = 'SELECT * FROM `members` WHERE `mail` = ? AND `pass` = ?';
		$member_check_stm = $dbh->prepare($member_check_sql);
		$member_check_stm->bindValue(1, $mail, PDO::PARAM_STR);
		$member_check_stm->bindValue(2, $pass, PDO::PARAM_STR);
		$member_check_stm->execute();
		$member_check_res = $member_check_stm->fetch(PDO::FETCH_ASSOC);
		//メールアドレスとパスワードが存在しているとき
		if ($member_check_res) {
			//idとnameを$_SESSIONに登録
			$_SESSION['member'] = array(
				'user_id' => $member_check_res['id'],
				'user_name' => $member_check_res['name'],
			);
		//メールアドレスとパスワードが存在していないとき
		} else {
			$error_arr[] = 'メンバーが存在しません。';
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
<title>ログイン処理結果</title>
</head>
<body>
<!--ログインエラーがない場合-->
<?php if (empty($error_arr)): ?>
<p>ログイン成功しました</p>
<!--新規投稿-->
<a href="./new_post_form.php">新規投稿</a>
<!--トップページへ戻る-->
<p>トップページへ<a href="./post_display.php">戻る</a></p>
<!--ログインエラーがある場合-->
<?php else: ?>
<?php foreach ($error_arr as $err_msg): ?>
<p><?php echo $err_msg; ?></p>
<?php endforeach; ?>
<p><a href="./login_form.php">ログイン</a>画面に戻る</p>
<?php endif; ?>
</body>
</html>

