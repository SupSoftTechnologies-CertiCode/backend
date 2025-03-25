<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Recognition</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0; /* Prevents extra space causing a second page */
        }
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            padding: 0;
            margin: 0;
            width: 297mm; /* A4 landscape width */
            height: 210mm; /* A4 landscape height */
            background: #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Prevents any spillover */
        }
        .certificate-container {
            width: 277mm; /* A4 landscape width minus border */
            height: 190mm; /* A4 landscape height minus border */
            padding: 10mm; /* Ensures the border doesn't push content */
            box-sizing: border-box;
            background: white;
            border: 10mm solid #c9a227; /* Gold border */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            font-size: 50px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 30px;
            font-weight: bold;
        }
        p {
            font-size: 18px;
            margin: 3px 0;
        }
        .recipient-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 10px 0;
        }
        .seminar-details {
            font-size: 20px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 18px;
        }
        .signature {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        .signature-line {
            width: 250px;
            border-top: 2px solid black;
            margin: 10px auto 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <h1>CERTIFICATE</h1>
        <h2>OF RECOGNITION</h2>
        <p>This certificate is proudly presented to</p>
        <div class="recipient-name">{{ $user_name }}</div>
        <p>For actively participating in the seminar entitled:</p>
        <div class="seminar-details">{{ $seminar_name }}</div>
        <p><strong>Topics Covered:</strong> {{ $seminar_topics }}</p>
        <p><strong>Held on:</strong> {{ $seminar_date }}</p>
        <div class="footer">
            <div class="signature">
                <span class="signature-line"></span>
                <br>Signature
            </div>
        </div>
    </div>
</body>
</html>