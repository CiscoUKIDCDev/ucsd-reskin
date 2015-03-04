<!doctype html>
<html>
<head>
<title>All Service Requests</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<style>
	div#sr_req {
		border: 1px solid #000080;
		margin-left: 15%;
		margin-right: 15%;
		padding: 1em;
		margin-top: 3em;
	}
	div#sr_req h3 {
		margin-top: 0;
		font-size: 1.2em;
	}
	p.completed {
		color: green;
	}
	p.failed {
		color: red;
	}
	p.in_progress {
		color: blue;
		font-weight: bold;
	}
</style>
</head>
<body>
<h1>Service Requests</h1>
<p><a href="index">&lt;&lt;Request a new Catalog Item</a></p>
<div id="sr_req">
{foreach from=$requests item=request}
<h3>#{$request.Number}: {$request.Name}</h3>
<table style="margin-bottom: 2em;">
<tr>
	<td style="padding-right: 1em;">
		Started:
	</td>
	<td>
		{$request.Started}
	</td>
</tr>
<tr>
	<td style="padding-right: 1em;">
		Task Owner:
	</td>
	<td>
		{$request.Owner}
	</td>
</tr>
<tr>
	<td>
		Status:
	</td>
	<td>
		{$request.Status}
	</td>
</tr>
<tr>
	<td>
		More info:
	</td>
	<td>
		<a href="{$request.url}">Link</a>
	</td>
</tr>
</table>
{/foreach}
</div>
<p style="margin: 1em;">
<a href="index">Request a new Service</a> |
<a href="status_all">View all Service Requests</a>
</p>
</body>
</html>
