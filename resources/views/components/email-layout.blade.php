@props(['title', 'name', 'message', 'url', 'buttonText'])

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

</head>
<body style="background-color: #F5F5F5; margin: 0; padding: 0; font-family: Inter, Arial, sans-serif;">

<!-- Outer table for background -->
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color: #F5F5F5; margin: 0; padding: 0;">
    <tr>
        <td align="center" style="padding: 40px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 340px; width: 100%;">

                <tr>
                    <td align="center" style="padding-bottom: 24px;">
                        <img alt="Logo" style="display: block; max-width: 100%; height: auto;"
                             src="{{ url('images/logo.png') }}">
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-bottom: 24px;">
                        <h1 style="font-family: Raleway, Arial, sans-serif; font-weight: 700; font-size: 24px; line-height: 38px; text-align: center; margin: 0; padding: 0; color: #000000;">
                            {{ $title }}
                        </h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding-bottom: 26px;">
                        <h2 style="font-family: Inter, Arial, sans-serif; font-weight: 400; font-size: 14px; margin: 0; padding: 0; color: #000000;">
                            Hi {{ $name }},
                        </h2>
                    </td>
                </tr>

                <tr>
                    <td style="padding-bottom: 26px;">
                        <p style="font-family: Inter, Arial, sans-serif; font-size: 14px; line-height: 1.6; margin: 0; padding: 0; color: #000000;">
                            {{ $message }}
                        </p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-bottom: 26px;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" style="background-color: #4B69FD; border-radius: 12px;">
                                    <a href="{{ $url }}"
                                       style="display: inline-block; background-color: #4B69FD; border-radius: 12px; padding: 10px 24px; color: #FFFFFF; font-weight: 600; font-family: Inter, Arial, sans-serif; font-size: 14px; text-decoration: none; width: 140px; text-align: center;">
                                        {{ $buttonText }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>
<style type="text/css">
    @media only screen and (min-width: 768px) {
        .content-table {
            max-width: 455px !important;
        }

        .title-text {
            font-size: 40px !important;
            line-height: 59px !important;
        }

        .body-text {
            font-size: 16px !important;
        }

        .greeting-text {
            font-size: 16px !important;
            padding-left: 11px !important;
        }

        .outer-padding {
            padding: 20px 0 0 0 !important;
        }
    }
</style>

</body>
</html>
