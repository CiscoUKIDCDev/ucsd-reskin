<!doctype html>
<html>
<head>
<title>Log in</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<h1>Log in</h1>
<p>{$reason}</p>
<form action="login" method="post">
<input type="hidden" name="redirect" value="{$redirect}" />
<table style="margin-left: 10%; border: 1px solid #000080; margin-right: 10%; width: 80%;">
<tr>
	<td>Username:</td>
	<td><input type="text" id="username" name="username" /></td>
</tr>
<tr>
	<td>Password:</td>
	<td><input type="password" id="password" name="password" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="Go" /></td>
</tr>
</table>

</form>
</body>
</html>
