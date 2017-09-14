<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>article</title>

    <link href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <link href="http://cdn.bootcss.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <style type="text/css">
        body {
            padding: 8px;
        }
    </style>
</head>

<body>
    <h2>{{ $article->title }}</h2>
    <p>{{ $author }}&nbsp;&nbsp;{{ $article->created_at }}</p>
    <p>{{ $article->content }}</p>
</body>

</html>