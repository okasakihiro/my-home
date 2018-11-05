<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
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

            .footer {
                margin-bottom: 30px;
                color: #636b6f;
                padding: 0 25px;
                font-size: 16px;
                font-weight: 600;
            }
        </style>


    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div>
                    <img id="under-construction" v-on:click="changeText" src="images/global/under_construction.png" alt="施工中....">
                </div>
                <div class="title m-b-md" id="content">
                    @{{ message }}
                </div>
                <div class="footer">
                    <span>
                        Power By 水原西守歌
                    </span>
                </div>
            </div>
        </div>
        <script src="{{ mix('js/app.js') }}"></script>
        <script>
            var underConstruction = new Vue({
                el: '#under-construction',
                methods: {
                    changeText: function () {
                        if (content.status === 1) {
                            content.message = '带我超巴！';
                            content.status  = 2;
                        } else {
                            content.message = '施工中....';
                            content.status  = 1;
                        }
                    }
                }
            });

            var content = new Vue({
                el: '#content',
                data: {
                    message: '施工中....',
                    status: 1
                }
            });

        </script>
    </body>
</html>
