<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leagues</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body class="container">
    @foreach($leagues as $league)
        <div class="card mb-3">
            <div class="card-body">
                <p>{{$league->name}}</p>
                <p>{{$league->name}}</p>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>user_id</th>
                        <th>score</th>
                        <th>order</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($league->members as $member)
                        <tr>
                            <td>{{$member->user_id}}</td>
                            <td>{{$member->score}}</td>
                            <td>{{$member->order}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</body>
</html>
