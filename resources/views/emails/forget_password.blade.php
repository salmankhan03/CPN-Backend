<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <style type="text/css" rel="stylesheet" media="all">
        @import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
                text-align: center !important
            }
        }

        @media only screen and (max-width: 600px) {

            .email-body_inner,
            .email-footer {
                width: 100% !important
            }
        }
    </style>
</head>

<body
    style="height: 100%; margin: 0; -webkit-text-size-adjust: none; font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; background-color: #F2F4F6; color: #51545E; width: 100% !important;">
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="width: 100%; margin: 0; padding: 0; -premailer-width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; background-color: #F2F4F6;">
        <tbody>
            <tr>
                <td align="center"
                    style="font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; font-size: 16px;">
                    <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                        style="width: 100%; margin: 0; padding: 0; -premailer-width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0;">
                        <tbody>
                            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation"
                                style="width: 570px; margin: 0 auto; padding: 0; -premailer-width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; background-color: #FFFFFF;">
                                <tr>
                                    <td class="email-masthead"
                                        style="font-family: emoji; font-size: 16px; padding: 10px 10px 10px 10px; text-align: center; background-color: #6d11cc;">
                                        <a class="f-fallback email-masthead_name"
                                            style="color: #ffff; font-size: 30px; font-weight: bold; text-decoration: none; text-shadow: 0 1px #FFFFFF;">
                                            Iherb Clone
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <!-- Email Body -->
                            <tr>
                                <td class="email-body" width="570" cellpadding="0" cellspacing="0"
                                    style="font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; font-size: 16px; width: 100%; margin: 0; padding: 0; -premailer-width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0;">
                                    <table class="email-body_inner" align="center" width="570" cellpadding="0"
                                        cellspacing="0" role="presentation"
                                        style="width: 570px; margin: 0 auto; padding: 0; -premailer-width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; background-color: #FFFFFF;">
                                        <!-- Body content -->
                                        <tbody>
                                            <tr>
                                                <td class="content-cell"
                                                    style="font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; font-size: 16px; padding: 30px 45px;">
                                                    <h1 style="margin-top: 0;text-align: center;font-size: 33px;">Reset
                                                        Password</h1>
                                                    <div class="f-fallback">
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1.625; color: #51545E;">
                                                            Hi {{$data['name']}} . We're confirming that...</p>
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1; color: #51545E;">
                                                            You had requested to reset your email for User account</p>
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1; color: #51545E;">
                                                            You need to clicking the link below and Reset your password
                                                            by loging </p>
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1; color: #51545E;">
                                                            You had requested to reset your password for User account by
                                                            clicking the below link.!</p>
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1; color: #51545E;">
                                                            <a href="{{ $data['resetUrl'] }}">Reset Password From
                                                                WebSite</a>
                                                        </p>
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1; color: #51545E;">
                                                            We'll always let you know when there is any activity on your
                                                            User account. This helps keep your account safe.</p>

                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1.625; color: #51545E; margin: 0;">
                                                            Thanks & Regards,</p>
                                                        <p
                                                            style="margin: 0.4em 0 1.1875em; font-size: 16px; line-height: 1; color: #51545E; margin: 0;">
                                                            User </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style="font-family: &quot;Nunito Sans&quot;, Helvetica, Arial, sans-serif; font-size: 16px;">
                                    <table class="email-footer" align="center" width="570" cellpadding="0"
                                        cellspacing="0" role="presentation"
                                        style="width: 570px; margin: 0 auto; padding: 0; -premailer-width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; text-align: center;">
                                        <tbody>
                                            <tr>
                                                <td class="content-cell" align="center"
                                                    style="font-family: emoji; font-size: 16px; padding: 5px; background-color: #6d11cc;">
                                                    <p class="f-fallback sub align-center"
                                                        style="font-size: 15px; line-height: 1.625; color: #ffff; text-align: center; margin: 0;">
                                                        © {{$data['year']}} by Iherb Clone</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <img src="https://ea.pstmrk.it/open?m=v3_1.AAAAAAAAAAAAAAAAAAAAAA.mOFqWbtHHs4wjbqpu2HcKkvj5bA61_h5-Lf39JhO6oLFPZ4zAd5s9TbhgzTlAvOYDt8stHOntS_yQIJP7pJ45EMtCb758q9OAai7OqmdjpcWRaWe1xzHlV0GrQsALfbBMsvLFcQD1mgjoiTFtPeb4_EHZItXBjMgk0GO30ZHuyOaT9zAj8HAdsR0HBvCCRnQyeGlV1UT8IwPL9WwUzKg6-oww5h1QZHtEJ8N2TXHzCZvdBWR2rCe9fa8nEe3p-RM_4CvRd7zABh-OXtU2n9i4I53fKL-yHUOahjbOtwHYviSTy6an_zfI-SGfxOa_1yZ5D9uNCh7cE8xEYAtdbN-lo0RJdp14g8UQwQaTraiSGVFqucP6m7GYb0EcC0H9-YNkaPV2aT-B6GA4BlAMGI82FXtXcDJ040Uv0ATbUpwbYACdDYxH4Vt1-LpT9xclpaIjyL-Fv5YivL2c7d2ZbVLLQ65AlQgHbDDRNaGqDmtVzWV_zSB9RoIc0I98LhInCBaDdi_PmbkX1OyW1HM9KHHHA"
        width="1" height="1" border="0" alt="" />
</body>

</html>