<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body style="background-color: #f1f1f1; padding: 0; margin: 0; font-family: 'Roboto', sans-serif;">

<div style="width: 600px; margin: 0 auto; background: #fff; padding: 0;">
    <table style="width: 100%;">
        <tr>
            <td style="background: #3D7EEB;">
                <table style="width: 100%;">
                    <tr>
                        <td style="vertical-align: top;">
                            <div style="padding-top:22px;">
                                <img src="https://beas.in/tsml/assets/images/logo-white.png" style="max-width: 188px;">
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            <div>
                                <img src="https://beas.in/tsml/assets/images/secondary-white.png" style="max-width: 50px; float: right;">
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px;">
                <p style="font-weight: 400; font-size: 15px; color: rgba(0,0,0,.9);">Dear Madam/Sir,</p>
                <!-- <p style="font-weight: 400; font-size: 15px; color: rgba(0,0,0,.9);">Greetings from tatasteelmining.com!</p> -->
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px;">
                <p style="font-weight: 400; font-size: 15px; line-height: 1.8; color: rgba(0,0,0,.8);">Your Sales Contarct, {{@$data['sc_no']}} is genrated against PO No. : {{@$data['po_no']}}. Please check it. </p>
                <!-- <p style="font-weight: 400; font-size: 15px; line-height: 1.8; color: rgba(0,0,0,.8);">It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> -->
            </td>
        </tr>
        <tr>
            <td style="padding: 0 15px;">
                <p style="font-weight: 400; font-size: 15px; color: rgba(0,0,0,.9);">Thank You,</p>
                <p style="font-weight: 400; font-size: 15px; color: rgba(0,0,0,.9);">Team-tatasteelmining.com</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 0; border-top: 2px solid #f1f1f1;">
                <p style="font-weight: 400; font-size: 15px; color: #fff; background: #09172F; text-align: center; padding: 10px 0; margin: 0;">© <?php echo date("Y"); ?> Tata Steel Mining Limited. All Rights Reserved.</p>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <p style="font-weight: 400; font-size: 15px; color: rgba(0,0,0,.9); text-align: right;">Powered by</p>
                        </td>
                        <td>
                            <img src="https://beas.in/tsml/assets/images/mjunction_logo.png">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

</body>
</html>