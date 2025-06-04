<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4A90E2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .status-approved {
            color: #2E7D32;
            font-weight: bold;
        }
        .status-rejected {
            color: #C62828;
            font-weight: bold;
        }
        .status-pending {
            color: #F9A825;
            font-weight: bold;
        }
        .notes {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #4A90E2;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dental Community</h1>
    </div>
    <div class="content">
        <p>Hello {{ $name }},</p>
        
        <p>We would like to inform you that the verification status of your professional profile has been updated.</p>
        
        <p>The current status of your profile is:
            @if($status == 'approved')
                <span class="status-approved">APPROVED</span>
            @elseif($status == 'rejected')
                <span class="status-rejected">REJECTED</span>
            @else
                <span class="status-pending">PENDING</span>
            @endif
        </p>

        @if($notes)
            <div class="notes">
                <h3>Notes from the verification team:</h3>
                <p>{{ $notes }}</p>
            </div>
        @endif

        @if($status == 'approved')
            <p>Congratulations! Your profile is now visible in our community. Patients will be able to find your professional information and contact you through the platform.</p>
        @elseif($status == 'rejected')
            <p>Please review the notes provided by our verification team and make the necessary corrections. Once updated, your profile will be reviewed again.</p>
        @else
            <p>Your profile is being reviewed by our verification team. We will notify you when there are updates.</p>
        @endif

        <p>To see more details or make changes, log in to your account and visit the "My Professional Profile" section.</p>
        
        <p>Thank you for being part of our community!</p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Dental Community - All rights reserved</p>
    </div>
</body>
</html>
