<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>ログイン</title>
</head>
<body>
<p>メールアドレスとパスワードを入力してログインしてください</p>
<form action="./session.php" method="POST">
<label>メールアドレス: <input type="text" name="mail" placeholder="procir@example.com"></label>
<label>パスワード: <input type="password" name="pass" pattern="^[0-9A-Za-z]+$" placeholder="半角英数字で入力"></label>
<input type="submit" value="ログイン">
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</form>
</body>
</html>
