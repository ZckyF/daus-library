<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #dddddd;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #9370db;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('logo-dasbry.png') }}" alt="Company Logo" class="mb-3" >
            <h1>Reset Password Request</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>We received a request to reset the password for your account. Please click the button below to reset your password. This link will expire in 3 hours.</p>
            <p><a href="{{ $link }}" class="button">Reset Password</a></p>
            <p>If you did not request a password reset, please ignore this email or contact support if you have any questions.</p>
            <p>Thank you,<br>Your Company Name</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
            <p>Your Company Address</p>
        </div>
    </div>
</body>
</html>
