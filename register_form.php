<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>会員登録画面</title>
</head>
<body>
<h1>会員登録をしてください</h1>
<form action="./register.php" method="POST">
<label>名前: <input type="text" name="name" placeholder="プロサー太郎"></label>
<label>メールアドレス: <input type="text" name="mail" placeholder="procir@example.com"></label>
<label>パスワード: <input type="password" name="pass" pattern="^[0-9A-Za-z]+$" placeholder="半角英数字で入力"></label>
<input type="submit" value="登録">
</form>
<p>既に会員登録をしている方は<a href="./login_form.php">ログイン</a>してください</p>
<p>トップページへ<a href="./post_display.php">戻る</a></p>
</body>
</html>
