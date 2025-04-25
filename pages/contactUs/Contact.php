<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../styles/common.css">
  <link rel="stylesheet" href="../../components/header/header.css">
  <link rel="stylesheet" href="../../components/footer/footer.css">
  <link rel="stylesheet" href="./contact.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <title>Contact Us</title>
</head>

<body id="top">
  <custom-header></custom-header>

  <div class="container1">
    <div class="form">
      <div class="contact-info">
        <h3 class="title">Let's get in touch</h3>
        <p class="text">Do you have a special request? Please contact us via our general contact form.</p>

        <div class="info">
          <div class="information">
            <img src="/assets/images/location.png" class="icon" alt="" />
            <p>75/4, Mihiripenna Road Dharga Town, Sri Lanka</p>
          </div>
          <div class="information">
            <img src="/assets/images/email.png" class="icon" alt="" />
            <p>safgems@live.com</p>
          </div>
          <div class="information">
            <img src="/assets/images/phone.png" class="icon" alt="" />
            <p>0772133434 - Mr.Manoj</p>
          </div>
        </div>

        <div class="social-media">
          <p><b>Connect with Us:</b></p>
          <div class="social-icons">
            <a href="#" class="b"><i class='bx bxl-facebook'></i></a>
            <a href="#" class="b"><i class='bx bxl-whatsapp'></i></a>
            <a href="#" class="b"><i class='bx bxl-instagram'></i></a>
          </div>
        </div>

        <div class="Appointment">
          <h3 class="title">Arrange an appointment</h3>
          <p class="text">You can arrange appointments directly with our partners.</p>
          <a href="../BookAppointmentPage/submit-appointment.php" class="btn">Book Your Appointment</a>
        </div>
      </div>

      <div class="contact-form">
        <?php
        include('./db.php'); 

        // Initialize variables
        $email = $phone = $name = $message = "";
        $success_message = "";
        $error_message = "";

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = htmlspecialchars($_POST['email'] ?? '');
            $phone = htmlspecialchars($_POST['phone'] ?? '');
            $name = htmlspecialchars($_POST['name'] ?? '');
            $message = htmlspecialchars($_POST['message'] ?? '');

            // Check if fields are empty
            if (empty($email) || empty($phone) || empty($name) || empty($message)) {
                $error_message = "❌ All fields are required.";
            } else {
                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO contactUs_inquries (email, phoneNo, name, message) VALUES (?, ?, ?, ?)");

                if (!$stmt) {
                    $error_message = "Prepare failed: " . $conn->error;
                } else {
                    $stmt->bind_param("ssss", $email, $phone, $name, $message);

                    if ($stmt->execute()) {
                        $success_message = "✅ Thank you for contacting us! We will get back to you soon.";

                        // Clear form fields after success
                        $email = $phone = $name = $message = "";
                    } else {
                        $error_message = "❌ Execution failed: " . $stmt->error;
                    }

                    // Close statement
                    $stmt->close();
                }
            }

            // Close connection
            $conn->close();
        }
        ?>

        <!-- Display Success or Error Messages -->
        <?php if (!empty($success_message)) : ?>
          <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)) : ?>
          <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Contact Form -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off">
          <h3 class="title">Contact us</h3>
          <div class="input-container">
            <input type="email" name="email" class="input" value="<?php echo htmlspecialchars($email); ?>" required pattern="/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address"/>
            <label for="email">Email</label>
            <span>Email</span>
          </div>
          <div class="input-container">
            <input type="tel" name="phone" id="phone" class="input" value="<?php echo htmlspecialchars($phone); ?>" required pattern="^\d{10}$" title="Please enter a 10-digit phone number" />
            <label for="phone">Phone</label>
            <span>Phone</span>
          </div>
          <div class="input-container">
            <input type="text" name="name" class="input" value="<?php echo htmlspecialchars($name); ?>" required />
            <label for="name">Username</label>
            <span>Username</span>
          </div>
          <div class="input-container textarea">
            <textarea name="message" class="input" required><?php echo htmlspecialchars($message); ?></textarea>
            <label for="message">Message</label>
            <span>Message</span>
          </div>
          <input type="submit" value="Send" class="btn" />
        </form>
      </div>
    </div>
  </div>

  <script src="contact.js"></script>
  <script src="../../components/header/header.js"></script>
  <script src="/components/footer/footer.js"></script>

  <script>
    // Load header
    fetch('/components/header/header.html')
      .then(response => response.text())
      .then(data => {
        document.getElementById('header-placeholder').innerHTML = data;
      });

    // Load footer
    document.addEventListener("DOMContentLoaded", function () {
      fetch('/components/footer/footer.html')
        .then(response => response.text())
        .then(data => {
          document.getElementById('footer-placeholder').innerHTML = data;
        });
    });
  </script>

  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>
