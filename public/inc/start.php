<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <link rel="stylesheet" href="inc/main.css">

    <title>Todos</title>
</head>

<body>
    <div class="container-lg">
        <header class="row flex-row-reverse px-5 py-2">
            <Button class="btn btn-outline-primary" id="toggler">
                <span class="rounded">&nbsp;</span>
                <span class="rounded">&nbsp;</span>
                <span class="rounded">&nbsp;</span>
            </Button>
        </header>
        <div class="row">
            <!-- Start Nav -->
            <nav class="col-xs-4 col-sm-3 col-md-2 pt-5 hidden" id="togglerTarget">
                <!-- <div class="profile row mb-5">
                    <a href="/user" class="mx-auto btn btn-outline-primary">
                        <i class="fas fa-user"></i>
                    </a>
                </div> -->
                <div class="profile row mb-5">
                    <a href="javascript:void(0)" class="mx-auto btn btn-outline-primary">
                        <?php if(isset($user->profile_img_url)): ?>
                            <img src="<?= $user->profile_img_url ?>">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="row">
                    <span class="mx-auto">
                        <span class="mb-2">Todos<a href="/todo"><i class="ml-2 fas fa-list-alt"></i></a></span><br>
                        <span class="mb-2">Edit Profile<a href="/user"><i class="ml-2 fas fa-user-edit"></i></a></span><br>
                        <span>Log Lout<a href="/user/logout"><i class="ml-2 fas fa-sign-out-alt"></i></a></span>
                    </span>
                </div>
            </nav>
            <!-- End Nav -->