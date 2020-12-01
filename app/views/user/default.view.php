<?php require_once 'inc/start.php'?>
<!-- Start Content -->
<div class="mx-5 px-5">
    <span class="badge badge-warning my-5"><small>Last Login: <?= $user->last_login ?></small></span>
    <div class="d-flex align-items-center justify-content-between mb-5">
        <button class="btn btn-outline-primary" id="editBtn">Edit Infos</button>
        <button class="btn btn-outline-danger ml-5" id="delAcc">Delete Account!</button>
    </div>
    <form>
        <fieldset class="row mb-5" disabled>
            <div class="col mr-5">
                <div class="form-group">
                    <!-- Profile Image -->
                    <div class="profile row mb-5" id="profile">
                        <a href="javascript:void(0)" class="mx-auto btn btn-outline-primary">
                            <?php if(isset($user->profile_img_url)): ?>
                                <img src="<?= $user->profile_img_url ?>">
                                <!-- <img src="https://thumbs.dreamstime.com/b/creative-illustration-default-avatar-profile-placeholder-isolated-background-art-design-grey-photo-blank-template-mockup-144849704.jpg" crossorigin="use-credentials"> -->
                                <!-- <img src="https://thumbs.dreamstime.com/b/creative-illustration-default-avatar-profile-placeholder-isolated-background-art-design-grey-photo-blank-template-mockup-144849704.jpg" crossorigin="anonymous"> -->
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </a>
                        <input type="file" accept=".jpg, .png, .gif, .jpeg" class="form-control hidden" name="profileImg">
                    </div>
                </div>
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" class="form-control" value="<?= $user->full_name ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" value="<?= $user->email ?>" disabled>
                </div>
            </div>
            <div class="col hidden passwd border-left">
                <div class="form-group">
                    <label for="oldpassword">Old Password</label>
                    <input type="password" id="oldpassword" class="form-control" name="oldpassword">
                    <label><small><a href="javascript:void(0)">Forgot Password</a></small></label>
                </div>
                <div class="form-group">
                    <label for="newpassword">New Password</label>
                    <input type="password" id="newpassword" class="form-control" name="newpassword">
                </div>
                <div class="form-group">
                    <label for="repnewpassword">Repeat the New Password</label>
                    <input type="password" id="repnewpassword" class="form-control" name="repnewpassword">
                </div>
                <input type="submit" class="btn btn-outline-primary" value="Submit">
            </div>
        </fieldset>
    </form>
</div>
<!-- End Content -->
<?php require_once 'inc/end.php'?>