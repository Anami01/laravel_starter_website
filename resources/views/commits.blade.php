<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<pre>
    @foreach($commits as $item)
    	@foreach($item as $val)
    		@php $data[] = $val @endphp
    	@endforeach
    @endforeach
	</pre>
	@php dd($data) @endphp
</body>
</html>