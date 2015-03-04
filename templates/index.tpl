<!doctype html>
<html>
<head>
<title>Request Catalog Item</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<h1>Request Catalog Item</h1>

<table rows="3" style="margin-left: 10%; border: 1px solid #000080; margin-right: 10%; width: 80%;">
{section name=categories loop=$categories}
<tr>
	<td style="padding-top: 1em; vertical-align: middle; text-align: center">
		<img src="http://{$categories[categories].IP_Addr}/{$categories[categories].Image}" />
	</td>
	<td style="padding-top: 1em;">
		<h3 style="padding: 0; margin: 0;">{$categories[categories].Catalog_Name}</h3>
		<p style="margin: 0; padding: 0;">
			{$categories[categories].Description}
		</p>
	</td>
	<td style="padding-left: 1em; vertical-align: middle; text-align: center; min-width: 15em;">
		<a href="{$categories[categories].url}" style="font-weight: bold; ">&gt;&gt;Request Service</a>
	</td>
</tr>
{/section}

</table>


</body>
</html>
