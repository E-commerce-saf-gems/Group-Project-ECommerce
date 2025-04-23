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
    <form action="resetPasswordUpdate.php" method="post">
  <label for="email">Email Address:</label>
  <input
    type="email"
    id="email"
    name="email"
    placeholder="Enter your email"
    required
  />
  <br />

  <label for="new-password">New Password:</label>
  <input
    type="password"
    id="new-password"
    name="new_password"
    placeholder="Enter new password"
    required
  />
  <br />

  <label for="confirm-password">Re-enter New Password:</label>
  <input
    type="password"
    id="confirm-password"
    name="confirm_password"
    placeholder="Re-enter new password"
    required
  />
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
