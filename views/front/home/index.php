<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" href="<?= url("assets/images/logo.png") ?>" type="image/png">
    <title><?= $title ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            text-align: center;
            padding: 60px 20px;
        }

        .logo {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 20px auto;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 28px;
        }

        p.tagline {
            margin: 0 0 25px;
            font-size: 16px;
            color: #475569;
        }

        p.details {
            margin: 0 auto 30px;
            max-width: 600px;
            color: #64748b;
            line-height: 1.5;
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            color: white;
        }

        .links a.github {
            background: #1e293b;
        }

        .links a.admin {
            background: #d4a116;
        }
    </style>
</head>

<body>
    <div class="logo"><img src="<?= url("assets/images/logo.png") ?>" width="120px" /></div>
    <h1><?= $heading ?></h1>
    <p class="tagline"><?= $tagline ?></p>
    <p class="details"><?= $details ?></p>
    <div class="links">
        <a href="<?= url("/admin") ?>" class="admin" target="_blank">Go to Admin</a>
        <a href="https://github.com/wasishah33/beezu" class="github" target="_blank">View on GitHub</a>
    </div>
</body>

</html>