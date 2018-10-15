<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Password Check</title>
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>

<body class="app">
    <div class='pos-a t-0 l-0 bgc-white w-100 h-100 d-f fxd-r fxw-w ai-c jc-c pos-r p-30'>
        <div class='mR-60'>
            <img alt='#' src='/images/pass1.png' />
        </div>

        <div class='d-f jc-c fxd-c'>
            <h1 class='mB-30 fw-900 lh-1 c-red-500' style="font-size: 40px;">Wait, Hol' up!</h1>
            <h3 class='mB-10 fsz-lg c-grey-900 tt-c'>Mandatory Password Change</h3>
            <span class='mB-20 fsz-def c-grey-700'>Before you start pls change the default password so that hackers
                won't
                just guess your password.</span>
            <form method="POST" id='update_password_form' id="needs-validation" novalidate>
                {{ csrf_field()}}
                <div class="form-group" id="passinput">
                    <label for="status_data"><small>New Password</small></label>
                    <input name="pass" id="pass" name="pass" type="password" class="form-control font-xs col-md-6"
                        placeholder="Password" required>
                </div>
                <button type="submit" class='btn btn-primary passChange'>Save</button>
                <a href="/logout" class="btn btn-danger ">Logout</a>
            </form>
            <div>

                

            </div>

        </div>
    </div>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
