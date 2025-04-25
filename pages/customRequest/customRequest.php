<?php

session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login/login.php?notloggedIn=1");
    exit;
}

$customerID = $_SESSION['customer_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/common.css">
    <link rel="stylesheet" href="./customRequest.css">
    <link rel="stylesheet" href="../../components/header/header.css">
    <link rel="stylesheet" href="../../components/footer/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <title>Custom Request</title>
</head>

<body id="top">
    <custom-header></custom-header>

    <main class="shipping-container">

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
                Your request has been sent successfully! <br> We will contact you shortly! Click Here to
                <u><a href="../Profile/Requests/MyRequest.php">View Requests</a></u>
            </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
            <div class="error-message">
                An error occurred while submitting your request! Try again!
            </div>
        <?php endif; ?>


        <form id="customRequestForm" action="./createCustomRequest.php" method="post">
            <div>
                <h3 class="h3" style="text-align: center;">Request a Custom Gemstone</h3>
                <br>
                <div class="form-group">
                    <label for="gem-type">Gemstone Type</label>
                    <select id="gem-type" name="gem-type" required>
                        <option value="">Select a Gemstone</option>
                        <option value="Diamond">Diamond</option>
                        <option value="Sapphire">Sapphire</option>
                        <option value="Ruby">Ruby</option>
                        <option value="Emerald">Emerald</option>
                        <option value="Amethyst">Amethyst</option>
                        <option value="Topaz">Topaz</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="color">Color</label>
                    <select id="color" name="color" required>
                        <option value="">Select Color</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="carat-weight">Carat Weight</label>
                    <input type="number" id="carat-weight" name="carat-weight" placeholder="1.5" step="0.1" min="0.1"
                        required>
                </div>

                <div class="form-group">
                    <label for="cut">Shape</label>
                    <select id="cut" name="cut" required>
                        <option value="">Select a Shape</option>
                        <option value="round">Round</option>
                        <option value="oval">Oval</option>
                        <option value="princess">Princess</option>
                        <option value="emerald">Emerald</option>
                        <option value="cushion">Cushion</option>
                        <option value="marquise">Marquise</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="special-requirements">Special Requirements</label>
                    <textarea id="special-requirements" name="special-requirements" rows="4"
                        placeholder="Specify any additional requirements, such as clarity, size dimensions, etc."></textarea>
                </div>

                <div style="display: flex; gap: 10px;" class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-primary">Clear Form</button>
                </div>
            </div>
        </form>
    </main>

    <custom-footer></custom-footer>

    <script>
        const gemColorMap = {
            Diamond: ["Colorless", "Blue", "Pink", "Yellow", "Green", "Brown"],
            Sapphire: ["Blue", "Pink", "Yellow", "White", "Green"],
            Ruby: ["Red", "Pink", "Purplish Red"],
            Emerald: ["Green", "Bluish Green"],
            Amethyst: ["Purple", "Violet"],
            Topaz: ["Blue", "Yellow", "Pink", "Clear", "Orange"]
        };

        document.getElementById("gem-type").addEventListener("change", function () {
            const gem = this.value;
            const colorSelect = document.getElementById("color");

            // Clear current options
            colorSelect.innerHTML = '<option value="">Select Color</option>';

            if (gemColorMap[gem]) {
                gemColorMap[gem].forEach(color => {
                    const option = document.createElement("option");
                    option.value = color;
                    option.textContent = color;
                    colorSelect.appendChild(option);
                });
            }
        });

    </script>

    <script src="../../components/footer/footer.js"></script>
    <script src="../../components/header/header.js"></script>
    <script src="./customRequest.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>