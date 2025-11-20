<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form Login</title>
    <link href="<?php echo base_url().'assets/' ?>login_style.css" rel='stylesheet' type='text/css' />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <script>
        function myFunction() {
            alert("Proses Login");
        }
    </script>
</head>
<body>
    <div class="main">
        <div class="user">
            <img src="<?php echo base_url().'assets/img/' ?>user.png" alt="gambar">
        </div>
        <div class="login">
            <div class="inset">
                <?php echo form_open('auth/login'); ?>
                <div>
                    <span style="text-align: center; padding-bottom: 10px;"><label>Login Form Customer</label></span>
                    <span><label>Username</label></span>
                    <span><input type="text" name="username" class="textbox" id="active" placeholder="Username" required></span>
                </div>
                <div>
                    <span><label>Password</label></span>
                    <span><input type="password" name="password" class="password" placeholder="Password" required></span>
                </div>
            <div class="sign">
                <div class="submit">
                    <input type="submit" name="submit" onClick="myFunction()" value="LOGIN" >
                </div>
                <span class="forget-pass">
                    <a href="<?php echo base_url('auth/form_reset'); ?>">Reset Password</a>
                    <div class="clear"> </div>
                    <a href="<?php echo base_url('auth/registrasi'); ?>">Registrasi</a>
                </span>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="copy-right">
    <p> Bayu Sukma</p>
</div>
</body>
</html>
