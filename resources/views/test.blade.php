<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <form method="POST" action="{{ action('Controller@performTest') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="type">Filtering type</label>
                    <select name="type" id="type">
                        <option value="pivot_joins">Pivot joins</option>
                        <option value="json_columns">Json column</option>
                        <option value="php_filter">PHP filter</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fields_numeric_selected">Numeric filters selected (max: {{ $max_numeric }})</label>
                    <input type="number" name="fields_numeric_selected" id="fields_numeric_selected"/>
                </div>

                <div class="form-group">
                    <label for="fields_choice_selected">Choice filters selected (max: {{ $max_choices }})</label>
                    <input type="number" name="fields_choice_selected" id="fields_choice_selected"/>
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit"/>
                </div>
            </form>
        </div>
    </body>
</html>
