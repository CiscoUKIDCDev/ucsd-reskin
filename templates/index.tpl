<!doctype html>
<html>
<head>
<title>Request Catalog Item</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<h1>Request Catalog Item</h1>
<p>Logged in as <strong>{$username}</strong> - <a href="logout">logout</a>.</p>

<table rows="3" style="margin-left: 10%; border: 1px solid #000080; margin-right: 10%; width: 80%;">
{foreach from=$items key=category item=catalog_entry}
<tr><td colspan="3"><h2>{$category}:</h2></td></tr>
{foreach from=$catalog_entry item=item}
<tr>
	<td style="padding-top: 1em; vertical-align: middle; text-align: center">
		<img src="http://{$item.IP_Addr}/{$item.Image}" style="max-width: 64px"/>
	</td>
	<td style="padding-top: 1em;">
		<h3 style="padding: 0; margin: 0;">{$item.Catalog_Name}</h3>
		<p style="margin: 0; padding: 0;">
			{$item.Description}
		</p>
	</td>
	<td style="padding-left: 1em; vertical-align: middle; text-align: center; min-width: 15em;">
		<a href="{$item.url}" style="font-weight: bold; ">&gt;&gt;Request Service</a>
	</td>
</tr>
{/foreach}
{/foreach}

</table>

<p style="margin-top: 1em; padding-left: 1em;">
	<a href="status_all">&gt;&gt;See all Service Requests</a>
</p>

</body>
</html>
