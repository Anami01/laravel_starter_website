<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<ul class="list-group">
    @foreach($items as $item)
        <li class="list-group-item">
            @if(isset($item['type']) && $item['type'] == 'file')
                <a href="/edit?repo={{ $repo }}&path={{ $item['path'] }}">{{ $item['name'] }}</a>
                <span class="badge">F</span>
            @else
                <a href="/finder?repo={{ $repo }}&path={{ $item['path'] }}">{{ $item['name'] }}</a>
                <span class="badge">D</span>
            @endif
        </li>
    @endforeach
</ul>
</body>
</html>