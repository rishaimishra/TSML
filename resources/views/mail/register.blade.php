<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <style type="text/css">
        .linkk:hover {
            background: #01b02e !important;
        }
    </style>
    <div style="max-width:640px; margin:0 auto;">
        <div
            style="/*width:620px;*/background:#F9F9F9; /*padding: 0px 10px;*/ border:1px solid #d9d8d8; border-bottom: none;height: 100px; margin: -9px 0px -13px 0px;">
            <div
                style="float: none; text-align: center; margin-top: 20px; background:url('{{ URL::to('#') }}') repeat center center">
                <img src="{{asset('public/admin/assets/images/logo.png')}}" width="135" alt="">
            </div>
        </div>
        <div style="max-width:620px; border:1px solid #d9d8d8; margin:0 0; padding:15px; ">

            <div style="display:block; overflow:hidden; width:100%;">

                Hello {{$data['first_name']}} {{$data['last_name']}},
                <p>
                    Thank you for registering in the eSales Portal of Tata Steel Mining. Please select the product and raise and RFQ here.
                    @if(@$data['type']=='register')
                    <p>Username : {{$data['email']}}</p>
                    <p>Password : {{$data['password']}}</p>
                    @endif
                </p>

            </div>

            <p style="font-family:Arial; font-size:14px; font-weight:500; color:#363839;margin: 0px 0px 10px 0px;">
                Regards<br>
               TSML
            </p>

        </div>
    </div>
</body>

</html>
