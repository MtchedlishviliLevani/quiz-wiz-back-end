@props(['title', 'name', 'message', 'url', 'buttonText'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
        }
          .responsive-title {
            font-size: 24px;
            line-height:38px;
        }
          .responsive-text {
            font-size: 14px;
        }
         .responsive-margin {
            margin-left: 0;
        }
        .content-w{
            max-width:340px;  
            padding: 40px 20px;

        }

         /* Desktop styles */
        @media (min-width: 768px) {
            .responsive-title {
                font-size: 40px;
                line-height:59px;
            }
              .responsive-text {
            font-size: 16px;
        }
         .responsive-margin {
            margin-left: 11px;
        }
        .content-w{
            max-width:455px;
            padding:20px 0 0 0;
        }
        }
        </style>
</head>
<body style="background-color: #F5F5F5; margin: 0; padding: 0; font-family: Inter, sans-serif;">
    
    <div class="content-w" style="width:100%; margin: 0 auto;">
        
        <div style="margin-bottom: 24px;">
            <div style="margin-bottom: 24px;">
                <img style="display: block; margin: 0 auto;" src="{{ asset('images/logo.svg') }}" alt="Logo">
            </div>
            <div>
                <h1 class="responsive-title" style="font-family: Raleway, sans-serif; font-weight: 700; text-align: center; margin: 0;">
                    {{ $title }}
                </h1>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:26px;">
            <h2 class="responsive-text responsive-margin" style="font-family: Inter, sans-serif;font-weight: 400;">
                Hi {{ $name }},
            </h2>
            <p class="responsive-text" style="font-family: Inter, sans-serif; line-height: 1.6;  ">
                {{ $message }}
            </p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" style="display: inline-block; background-color: #4B69FD; border-radius: 12px; padding: 10px 24px; color: #FFFFFF; font-weight: 600; font-family: Inter, sans-serif; text-decoration: none; width: 188px;">
                    {{ $buttonText }}
                </a>
            </div>
        </div>
        
    </div>

</body>
</html>