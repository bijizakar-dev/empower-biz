<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login &mdash; Empower Biz</title>
    <link href="<?= base_url()?>/template/css/styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="<?= base_url()?>/template/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background-color: #1f3a93;
            font-family: Arial, sans-serif;
        }
        .left-section {
            flex: 8;
            overflow: hidden;
            position: relative;
        }
        .left-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1593642532842-98d0fd5ebc1a?fit=crop&w=1200&q=80') no-repeat center center;
            background-size: cover;
            animation: zoomInOut 20s ease-in-out infinite alternate;
        }
        @keyframes zoomInOut {
            0% {
                transform: scale(1) translateX(0);
            }
            100% {
                transform: scale(1.1) translateX(0);
            }
        }
        .right-section {
            flex: 4;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6f9;
            padding: 20px;
            box-shadow: 0 8px 8px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 100%;
            width: 100%;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
        }
        .container h1 {
            text-align: start;
            margin-bottom: 10px;
            color: #1f3a93;
        }
        .welcome-message {
            text-align: start;
            margin-bottom: 20px;
            font-size: 14px;
            color: #333;
            opacity: 50%;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-check {
            margin-bottom: 15px;
        }
        .form-check input {
            margin-right: 10px;
        }
        .form-check label {
            color: #333;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #1f3a93;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #16316e;
        }
        .card-footer {
            text-align: center;
            margin-top: 15px;
        }
        .card-footer a {
            color: #1f3a93;
            text-decoration: none;
        }
        .card-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .left-section {
                display: none;
            }
            .right-section {
                flex: 1;
                padding: 20px;
            }
            .container {
                padding: 20px;
            }
            .welcome-message {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body class="bg-primary">
    <div class="left-section"></div>
    <div class="right-section">
        <div class="container">
            <h1>Empower Biz</h1>
            <p class="welcome-message">Silahkan login terlebih dahulu.</p>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="far fa-flag"></i> <?= $error; ?>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <!-- Login form-->
                <form action="<?= base_url('auth/login') ?>" method="POST">
                    <!-- Form Group (email address)-->
                    <div class="form-group">
                        <label class="small mb-1" for="inputEmailAddress">Username / Email</label>
                        <input class="form-control" id="inputEmailAddress" type="text" name="identity" placeholder="Enter username or email address" required />
                    </div>
                    <!-- Form Group (password)-->
                    <div class="form-group">
                        <label class="small mb-1" for="inputPassword">Password</label>
                        <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Enter password" required />
                    </div>
                    <!-- Form Group (login box)-->
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <div class="col-lg-6 small"></div>
                        <div class="col-lg-6 small">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center mt-5">
                <div class="small" style="font-size: 12px; opacity: 50%">Copyright © Empower Biz 2024</div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?= base_url()?>/template/js/scripts.js"></script>
</body>
</html>
