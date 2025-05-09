


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="login.css" />
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../styles/common.css">
 
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
  </head>

 

  <body>
    <custom-header></custom-header>
<main>
    <div class="login-container">
      <div class="login-section">
        <h1>Login in to your account</h1>
        <p>This is required to leave bids, register to bid or purchase gems</p>

        <?php if (isset($_GET['fail']) && $_GET['fail'] == 1): ?>
            <div class="error-message">
                Invalid password or email! Try Again
            </div>
        <?php elseif(isset($_GET['fail']) && $_GET['fail'] == 2) : ?>
            <div class="error-message">
                Invalid password or email! Try Again
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['notloggedIn']) && $_GET['notloggedIn'] == 1): ?>
            <div class="error-message">
                Login First.
            </div>
        <?php endif; ?>
        
        <form action="logins.php" method="POST">
          <label for="email">Email Address </label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
          />
         </br>
          <label for="password">Password </label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
            required
          />
        </br>

       <!-- <div class="terms">
            <input type="checkbox" id="terms">
            <label for="terms">I accept terms & conditions</label>
        </div>-->
       
            <button type="submit" name="login_now_btn" class="login-btn">Login</button>

        </form>
        <a href="forgot-password.html" class="forgot-password">Forgot Password ?</a>
      </div>

      <div class="register-section">
        <h2>Don’t have an account?<br><br>Register today</h2>
        <br>
        <p>
            Registration is free and straightforward. An account with SAF Gems
            gives you the opportunity to leave bids, register to bid live in our
            online sales, and receive marketing updates for items of interest.
          </p>
        <div class="button-section">
        <button type="button" onclick="window.location.href='../RegisterPage/register.html'">Create Account</button>
        </div>
       
       
      </div>
    </div>
  </main>
  <script src="script.js"></script>
    <script src="../../components/header/header.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
 
  </body>
</html>