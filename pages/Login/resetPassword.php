<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../RegisterPage/register.css" />
   
  </head>
  <body>
    

    <div>
      <form action="login.php" method="get">
        <label for="email">Email Address : </label>
        <input
          type="email"
          id="email"
          placeholder="Enter your eamil"
          required
        />
        <br />

        <label for="new-password">New Password : </label>
        <input
          type="password"
          id="new-password"
          placeholder="Enter new password"
          required
        />
        <span id="new-password-error" class="error-msg"></span>
        <br />

        <label for="confirm-password">Re- enter New Password : </label>
        <input
          type="password"
          id="confirm-password"
          placeholder="Re-enter new password"
          required
        />
        <span id="confirm-password-error" class="error-msg"></span>
        <br />

        <div class="button-section">
          <button type="button" onclick="loginpage()">Cancel</button>
          <button type="submit">Save</button>
        </div>
      </form>
      <script src="password.js"></script>
    </div>

    
  </body>
</html>
