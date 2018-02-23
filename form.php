<?php
    $errors = [];
    $missing = [];
    if (isset($_POST['send'])) {
        $expected = ['name', 'email', 'comments', 'gender', 'terms', 'extras', 'os', 'format'];
        $required = ['name', 'comments', 'gender', 'terms', 'os'];
        $to = 'Alan Doyle <alan@example.com>';
        $subject = "Feedback from online form";
        $headers = [];
        $headers[] = 'From: webmaster@example.com';
        $headers[] = 'Cc: another@example.com';
        $headers[] = 'Content-type: text/plain; charset=utf-8';
        // Check with hosting company for correct format for particular server
        $authorized = null;
        // Check for the existence of the following
        if (!isset($_POST['gender'])) {
            $_POST['gender'] = '';
        }

        if (!isset($_POST['terms'])) {
            $_POST['terms'] = '';
        }

        if (!isset($_POST['extras'])) {
            $_POST['extras'] = [];
        }

        // Requires a minimum selection
        $minimumChecked = 2;
        if (count($_POST['extras']) < $minimumChecked) {
            $errors['extras'] = true;
        }

        if (!isset($_POST['format'])) {
            $_POST['format'] = [];
        }

        // Requires a minimum selection
        $minimumSelected = 2;
        if (count($_POST['format']) < $minimumSelected) {
            $errors['format'] = true;
        }

        require './includes/process_mail.php';
        // if ($mailSent) {
        //     header('Location: thanks.php');
        //     exit;
        // }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>feedback Form</title>
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
    <h1>Contact Us</h1>
    <?php if ($_POST && ($suspect || isset($errors['mailfail']))) : ?>
    <p class="warning">Sorry, your mail couldn't be sent.</p>
    <?php elseif ($errors || $missing) : ?>
        <p class="warning">Please fix the item(s) indicated</p>
    <?php endif; ?>
    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <p>
            <label for="name">Name:
            <?php if ($missing && in_array('name', $missing)) : ?>
                <span class="warning">Please enter your name</span>
            <?php endif; ?>
            </label>
            <input type="text" name="name" id="name"
                <?php
                    if($errors || $missing) {
                        echo 'value="' . htmlentities($name) . '"';
                    }
                ?>
            >
        </p>
        <p>
            <label for="email">Email:
                <?php if ($missing && in_array('email', $missing)) : ?>
                    <span class="warning">Please enter your email address</span>
                    <?php elseif (isset($errors['email'])) : ?>
                        <span class="warning">Invalid email address</span>
                <?php endif; ?>
            </label>
            <input type="email" name="email" id="email"
                <?php
                    if($errors || $missing) {
                        echo 'value="' . htmlentities($email) . '"';
                    }
                ?>
            >
        </p>
        <p>
            <label for="comments">Comments:
                <?php if ($missing && in_array('comments', $missing)) : ?>
                    <span class="warning">You forgot to add any comments</span>
                <?php endif; ?>
            </label>
            <textarea name="comments" id="comments"><?php 
                if ($errors || $missing) {
                    echo htmlentities($comments);
                }
            ?></textarea>
        </p>

        <!-- Simple check box -->

        <p>
            <input type="checkbox" name="terms" id="terms" value="agreed"
                <?php
                    if ($_POST && $terms == 'agreed') {
                        echo 'checked';
                    }
                ?>
            >
                <label for="terms">I agree to the terms and conditions
                    <?php if ($missing && in_array('terms', $missing)) : ?>
                    <span class="warning">Please signify acceptance</span>
                <?php endif; ?>
                </label>
        </p>
        
        <!-- Handling one checkbox -->

        <fieldset>
            <legend>Gender: <?php if ($missing && in_array('gender', $missing)) : ?>
                <span class="warning">Please select a value</span>
            <?php endif; ?>
            </legend>
            <p>
                <input type="radio" name="gender" value="female" id="gender_f"
                    <?php
                        if ($_POST && $gender == 'female') {
                            echo 'checked';
                        }
                    ?>
                >
                    <label for="gender_f">Female</label>
                <input type="radio" name="gender" value="male" id ="gender_m"
                    <?php
                        if ($_POST && $gender == 'male') {
                            echo 'checked';
                        }
                    ?>
                >
                    <label for="gender_m">Male</label>
                <input type="radio" name="gender" value="won't say" id="gender_0"
                    <?php
                        if (!$_POST || $gender == "won't say") {
                            echo 'checked';
                        }
                    ?>
                >
                    <label for="gender_0">Rather not say</label>
            </p>
        </fieldset>

        <!-- Handling multiple checkboxes -->

        <fieldset>
            <legend>Optional Extras
                <?php if (isset($errors['extras'])) : ?>
                    <span class="warning">Please select at least <?= $minimumChecked; ?></span>
                    <?php endif; ?>      
            </legend>
            <input type="checkbox" name="extras[]" value="sun roof" id="extras_0"
                <?php
                    if ($_POST && in_array('sun roof', $extras)) {
                        echo 'checked';
                    }
                ?>
            >
                <label for="extras_0">Sun roof</label>
                <br>
            <input type="checkbox" name="extras[]" value="aircon" id="extras_1"
                <?php
                    if ($_POST && in_array('aircon', $extras)) {
                        echo 'checked';
                    }
                ?>
            >
                <label for="extras_1">Air conditioning</label>
                <br>
            <input type="checkbox" name="extras[]" value="automatic" id="extras_2"
                <?php
                    if ($_POST && in_array('automatic', $extras)) {
                        echo 'checked';
                    }
                ?>
            >
                <label for="extras_2">Automatic transmission</label>
                <br>
        </fieldset>

        <!-- Dropdown menu -->

        <p>
            <label for "os">Operating System
                <?php if ($missing && in_array('os', $missing)) : ?>
                    <span class="warning">Please make a selection</span>
                <?php endif; ?>
            </label>
            <select name="os" id="os">
                <option value=""
                    <?php
                        if (!$_POST || $os == '') {
                            echo 'selected';
                        }
                    ?>
                >Please make a selection</option>
                <option value="Linux"
                    <?php
                        if ($_POST && $os == 'Linux') {
                            echo 'selected';
                        }
                    ?>
                >Linux</option>
                <option value="Mac"
                    <?php
                        if ($_POST && $os == 'Mac') {
                            echo 'selected';
                        }
                    ?>
                >Mac OS X</option>
                <option value="Windows"
                    <?php
                        if ($_POST && $os == 'Windows') {
                        echo 'selected';
                        }
                    ?>
                >Windows</option>
            </select>   
        </p>

        <!-- Multiple Choice List -->

        <p>
            <label for="format">Select the formats you require:
                <?php if (isset($errors['format'])) : ?>
                    <span class="warning">Please select at least <?= $minimumSelected ?></span>
                <?php endif; ?>
            </label>
            <select name="format[]" id="format" size="3" multiple>
                <option value="PDF"
                    <?php 
                       if ($_POST && in_array('PDF', $format)) {
                           echo 'selected';
                       } 
                    ?>
                >PDF</option>
                <option value="ePub"
                    <?php 
                       if ($_POST && in_array('ePub', $format)) {
                           echo 'selected';
                       } 
                    ?>
                >ePub</option>
                <option value="mobi"
                    <?php 
                       if ($_POST && in_array('mobi', $format)) {
                           echo 'selected';
                       } 
                    ?>
                >MOBI</option>
            </select>
        </p>
        
        <p>
            <input type="submit" name="send" id="send" value="Send Comments">
        </p>
    </form>
</body>
</html>