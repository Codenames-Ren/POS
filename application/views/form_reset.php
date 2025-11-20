<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>form login</title>
    <link href="<?php echo base_url().'assets/' ?>login_style.css" rel="stylesheet" type='text/css'/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<script>
    function myFunction()
    {
        alert("Proses Login")
    }
</script>
</head>
<body>
    <div class="main">
        <div class="user">
            <img src="<?php echo base_url().'assets/img/' ?>user.png" alt="">
        </div>
        <div class="login">
            <div class="inset">
                <?php echo form_open('auth/edit'); ?>
                <div>
                <span><label>Form Ubah Password</label></span>
                    <span><label>Username</label></span>
                    <span><input type="text" name="username" class="textbox" id="active" placeholder="Username" required></span>
                </div>
                <div>
                    <span><label>Old Password</label></span>
                    <span><input type="password" name="password" class="password" id="active" placeholder="Old Password" required></span>
                </div>
                <div>
                    <span><label>New Password</label></span>
                    <span><input type="password" name="password_new" class="password" id="active" placeholder="New Password" required></span>
                </div>
                <div class="sign">
                    <div class="submit">
                        <input type="submit" name="submit" value="UBAH">
                    </div>
                    <span class="forget-pass">
                        <a href="<?php echo base_url('auth/login'); ?>">Login</a>
                            <div class="clear"></div>
                        <a href="<?php echo base_url('auth/registrasi'); ?>">Registrasi</a>
                    </span>
                        <div class="clear"></div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="copy-right">
        <p>Bayu Sukma</p>
    </div>
</body>
</html>