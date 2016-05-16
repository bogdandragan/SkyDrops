<style>
	body{
		color: #333;
		background: #ffffff;
		font-family: Arial, Tahoma;
		font-size: 14px;
	}
</style>

<body>
	<h1>SKyDrops</h1>
	<p>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} wants to share a Drop with you.</p>
	@if(!empty($mailMessage))
	<p><strong>Message</strong><br>{!! nl2br($mailMessage) !!}</p>
	@endif
	<a href="http://skydrops.skypro.ch/d/{{ $drop_hash }}">http://skydrops.skypro.ch/d/{{ $drop_hash }}</a>
</body>