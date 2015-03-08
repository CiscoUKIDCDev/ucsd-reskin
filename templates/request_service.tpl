<!doctype html>
<html><head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<title>Request Catalog Item - {$Catalog_Name}</title>
</head>
<body>
<h1>Customise Service Request</h1>
<p>Logged in as <strong>{$username}</strong> - <a href="logout">logout</a>.</p>
<div style="border: 1px solid #000080; margin-left: 10%; margin-right: 10%; padding: 1.5em; margin-bottom: 2em;">
	<h3>
		<img src="http://{$IP_Addr}/{$Image}" />
		{$Catalog_Name}
	</h3>
	<p>{$Description}</p>
	<form action="submit_api_request" method="post">
		<input type="hidden" name="Catalog_Name" value="{$Catalog_Name}" />
<table>
{foreach from=$inputs item=input}
<tr>
<td style="padding-right: 1em;">
	{$input.label}
</td>
<td>
	{$input.form}
</td>
</tr>
{/foreach}
<tr>
<td>
	&nbsp;
</td>
<td>
	&nbsp;
</td>
<td>
	<input type="submit" value="&gt;&gt; Submit" />
</td>
</tr>
</table>
</form>
</div>
<a href="index">&lt;&lt;Go Back to main catalog</a>
</body>
</html>

