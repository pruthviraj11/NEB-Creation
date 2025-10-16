<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
</head>
<body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;'>
    <div style='background-color: #ffffff; max-width: 600px; margin: 20px auto; padding: 0; border: 1px solid #dddddd;'>
        
        <!-- Header -->
        <div style='background-color: #17365d; color: white; text-align: center; padding: 15px;'>
            <h3 style='margin: 0; font-size: 24px;'>Contact Us Form</h3>
        </div>

        <!-- Content -->
        <div style='padding: 20px;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding: 8px; font-weight: bold; color: #555555; width: 30%;'>Name</td>
                    <td style='padding: 8px; color: #222222;'>{{ $contactInfo->name }}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; font-weight: bold; color: #555555;'>Email</td>
                    <td style='padding: 8px; color: #222222;'>{{ $contactInfo->email }}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; font-weight: bold; color: #555555;'>Message</td>
                    <td style='padding: 8px; color: #222222;'>{{ $contactInfo->message }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div style='background-color: #f1f1f1; text-align: center; padding: 10px; font-size: 12px; color: #777777;'>
            This message was sent from your website's contact form.
        </div>
    </div>
</body>
</html>