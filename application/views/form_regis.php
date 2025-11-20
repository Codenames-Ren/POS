<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>form login</title>
    <link href="<?php echo base_url().'assets/' ?>login_style.css" rel='stylesheet' type='text/css' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<script>
    function myFunction()
    {
        alert("Proses Registrasi Berhasil")
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
                <?php echo form_open('auth/regis'); ?>
                <div>
                <span><label>Form Registrasi</label></span>
                    <span><label>Nama Lengkap</label></span>
                    <span><input type="text" name="nama" class="textbox" id="active" placeholder="Nama Lengkap" required></span>
                </div>
                <div>
                    <span><label>Username</label></span>
                    <span><input type="text" name="username" class="textbox" id="active" placeholder="Username" required></span>
                </div>
                <div>
                  <span><label>Password New</label></span>
                  <span><input type="password" name="password" class="password" placeholder="Password" required></span>
                </div>
                <div class="sign">
                    <div class="submit">
                        <input type="submit" name="submit" onclick="myFunction()" value="REGIS">
                    </div>
                    <span class="forget-pass">
                        <a href="<?php echo base_url('auth/login'); ?>">Login</a>
                            <div class="clear"></div>
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