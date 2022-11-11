<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Exception</title>

    <style>
        .exception-container {
            text-align: center;
        }

        .exception-title {
            color: #009879;
            direction: ltr;
            font-family: sans-serif;
        }

        .exception-table {
            max-width: 800px;
            display: inline-block;
            border-collapse: collapse;
            margin: 0 0 25px;
            font-size: 0.9em;
            font-family: sans-serif;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .exception-table tbody {
            width: 100%;
        }

        .exception-table tbody tr {
            background-color: rgb(255, 255, 255);
        }

        .exception-table tbody th {
            width: 80px;
            background-color: #009879;
            color: #ffffff;
            text-align: left;
            padding: 12px 15px;
        }

        .exception-table tbody tr:not(:last-child) th {
            border-bottom: 1px solid rgba(0, 0, 0, .1);
        }

        .exception-table tbody td {
            padding: 12px 15px;
        }

        .exception-table tbody tr:not(:last-child) td {
            border-bottom: 1px solid #dddddd;
        }
    </style>
</head>
<body>
<div class="exception-container">
    <h2 class="exception-title">Error occurred:</h2>
    <table class="exception-table">
        <tbody>
        <tr>
            <th>
                Type
            </th>
            <td><?= get_class($e); ?></td>
        </tr>
        <tr>
            <th>
                Message
            </th>
            <td>
                <?= preg_replace('/(#\d+[^#]*)/u', '<br><br>' . '{$1}', $e->getMessage()); ?>
            </td>
        </tr>
        <tr>
            <th>
                File
            </th>
            <td><?= $e->getFile(); ?></td>
        </tr>
        <tr>
            <th>
                Line
            </th>
            <td><?= $e->getLine(); ?></td>
        </tr>
        <tr>
            <th>
                Trace
            </th>
            <td>
                <?php
                preg_match_all('/#\d+[^#]*/m', $e->getTraceAsString(), $matches);
                ?>
                <?php foreach ($matches[0] as $trace): ?>
                    <p><?= $trace; ?></p>
                <?php endforeach; ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
