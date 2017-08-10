<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Title</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <style>

    </style>
</head>
<body>
<script>
    $(document).ready(function () {

        function refreshPage() {
            $.ajax({
                method: "GET",
                url: "{{route("account")}}",
                success: function (data) {

                    var table = document.getElementById('table_body');
                    var html = '';

                    for (var i = 0; i < 5; i++) {
                        html += '<tr>';
                        for (var j = 0; j < data.length; j++) {
                            // Проверка существования картинки
                            if (j === 4) {
                                if (data[j][i]) {
                                    html += '<td><img src=' + data[j][i] + '></td>';
                                }
                                else {
                                    html += '<td><img src=' + 'http://www.hot-motor.ru/body/clothes/images/no_icon.png' + ' width="150" height="150"></td>';
                                }
                                continue;
                            }
                            // Проверка комментариев на существование
                            if(j === 2 && !data[j][i]){
                                html += '<td> Комментариев нет</td>';
                                continue;
                            }
                            html += "<td>" + data[j][i] + "</td>";
                        }
                        html += '</tr>';
                    }
                    table.innerHTML = html;
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

        var timer = setInterval(refreshPage, 4000);
        timer.start();

    });


</script>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Сообщение</th>
        <th>Лайки</th>
        <th>Комментарии</th>
        <th>Количество</th>
        <th>Картинка</th>
        <th>Поделились</th>
    </tr>
    </thead>
    <tbody id="table_body">

    </tbody>
</table>
</body>
</html>
