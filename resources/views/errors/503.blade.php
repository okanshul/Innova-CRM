<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance - {{ setting('app_name', 'InnovaCRM') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .maintenance-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <div class="icon-circle">
            <i class="fa-solid fa-wrench"></i>
        </div>
        <h3 class="fw-bold mb-2">{{ setting('app_name', 'InnovaCRM') }} is Under Maintenance</h3>
        <p class="text-secondary mb-4">We are performing scheduled system updates to improve performance and security. We'll be back online shortly.</p>
        <div class="d-flex justify-content-center gap-2">
            <a href="/login" class="btn btn-outline-light rounded-3 px-4 py-2 fw-semibold">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Admin Login
            </a>
        </div>
    </div>
</body>
</html>
