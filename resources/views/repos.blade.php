<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="list-group">
        @foreach($repos as $repo)
            <a class="list-group-item" href="/finder?repo={{ $repo['name'] }}">
                <h4 class="list-group-item-heading">{{ $repo['name'] }}</h4>
                <p class="list-group-item-text">{{ $repo['description'] }}</p>
            </a>
        @endforeach
    </div>

</body>
</html>