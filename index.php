<?php
session_start();

if (!isset($_SESSION['upper_bound'])) {
    $_SESSION['upper_bound'] = 20;
}
if (!isset($_SESSION['number'])) {
    $_SESSION['number'] = rand(1, $_SESSION['upper_bound'] - 1);
}
if (!isset($_SESSION['guesses'])) {
    $_SESSION['guesses'] = 0;
}
if (!isset($_SESSION['display_cookies'])) {
    $_SESSION['display_cookies'] = 0;
}
if (!isset($_SESSION['previous_guesses'])){
    $_SESSION['previous_guesses'] = array();
}


$default_background_color = 'aliceblue';
$default_font_size = '14pt';
$default_player_name = 'Onur';

if (!isset($_COOKIE['background_color'])) {
    setcookie('background_color', $default_background_color, time() + 30);
}
if (!isset($_COOKIE['font_size'])) {
    setcookie('font_size', $default_font_size, time() + 30);
}
if (!isset($_COOKIE['player_name'])) {
    setcookie('player_name', $default_player_name, time() + 30);
}

if (isset($_POST['upper_bound_submit'])) {
    $_SESSION['upper_bound'] = (int)$_POST['upper_bound'];
    $_SESSION['number'] = rand(1, $_SESSION['upper_bound'] - 1);
    $_SESSION['guesses'] = 0;
    $_SESSION['previous_guesses'] = array();
}
if (isset($_POST['generate_submit'])) {
    $_SESSION['number'] = rand(1, $_SESSION['upper_bound'] - 1);
    $_SESSION['guesses'] = 0;
    $_SESSION['previous_guesses'] = array();
}
if (isset($_POST['guess_submit'])) {
    $guess = (int)$_POST['guess'];
    $_SESSION['guesses']++;
    $_SESSION['previous_guesses'][] = $guess;
    if ($guess < $_SESSION['number']) {
        $message = 'Your Guess Is Too Small.';
    } elseif ($guess > $_SESSION['number']) {
        $message = 'Your Guess Is Too Large.';
    } else {
        $message = 'Your Guess Is Correct!';
    }
}
if(isset($_POST['cookies_submit'])){
    if((int) $_SESSION['display_cookies'] == 0){
        $_SESSION['display_cookies'] = 1;
    }else{
        $_SESSION['display_cookies'] = 0;
    }
}

$background_color = $_COOKIE['background_color'] ?? $default_background_color;
$font_size = $_COOKIE['font_size'] ?? $default_font_size;
$player_name = $_COOKIE['player_name'] ?? $default_player_name;

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Lab08 - PHP Guessing Game</title>
        <style>
            body {
                background-color: <?php echo $background_color; ?>;
                font-size: <?php echo $font_size; ?>;
            }
        </style>
    </head>
    <body>
    <h3>Hello, <?php echo $player_name; ?>!</h3>
    <p>Guess A Number Between 1 And <?php echo $_SESSION['upper_bound'] - 1; ?>.</p>
    <form method="post">
        <label for="upper_bound">Set The Generated Number Upper Bound (1 to (n-1)):</label>
        <input type="number" id="upper_bound" name="upper_bound" min="1" value="<?php echo $_SESSION['upper_bound']; ?>">
        <input type="submit" name="upper_bound_submit" value="Set">
    </form>
    <br>
    <form method="post">
        <input type="submit" name="generate_submit" value="Generate Number">
    </form>
    <br>
    <form method="post">
        <label for="guess">Input a number:</label>
        <input type="number" id="guess" name="guess" min="0" max="<?php echo $_SESSION['upper_bound'] - 1; ?>">
        <input type="submit" name="guess_submit" value="Guess">
    </form>
    <p>Guessing Story</p>
    <ul>
        <?php
        if ($_SESSION['guesses'] == 0) {
            echo '<li>You Made No Guesses Yet.</li>';
        } else {
            if (isset($message) && $message == 'Your Guess Is Correct!') {
                echo '<li>Congratulations! You Guessed Number ' . $_SESSION['number'] . ' In ' . $_SESSION['guesses'] . ' guesses! Another Number Has Been Generated, You Can Keep Guessing!</li>';
                $_SESSION['guesses'] = 0;
                $_SESSION['previous_guesses'] = array();
                $_SESSION['number'] = rand(1, $_SESSION['upper_bound'] - 1);
            } else {
                echo '<li>You Made ' . $_SESSION['guesses'] . ' ' . ($_SESSION['guesses'] == 1 ? 'Guess' : 'Guesses') . '.</li>';
                if (isset($message)) {
                    echo '<li>' . $message . '</li>';
                }
                echo '<li>Previous Guesses : ';
                foreach ($_SESSION['previous_guesses'] as $key => $value) {
                    if ($key > 0) echo ',';
                    echo $value;
                }
                echo '</li>';
            }
        }
        ?>
    </ul>

    <hr>

    <form method="post">
        <h3>Change Style</h3>
        <label for="background_color">Background Color :</label>
        <input type="color" id="background_color" name="background_color" value="<?php echo $_COOKIE['background_color'] ?? $default_background_color; ?>">
        <input type="submit" name="background_color_submit" value="Set">
        <br><br>
        <label for="font_size">Font Size :</label>
        <input type="range" id="font_size" name="font_size" min="10" max="20" step="2"
               value="<?php echo isset($_COOKIE['font_size']) ? str_replace('pt', '', $_COOKIE['font_size']) : $default_font_size; ?>">
        <input type="submit" name="font_size_submit" value="Set">
        <br><br>
        <label for="player_name">Player Name :</label>
        <input type="text" id="player_name" name="player_name" value="<?php echo $_COOKIE['player_name'] ?? $default_player_name; ?>">
        <input type="submit" name="player_name_submit" value="Set">
    </form>
    <br><br>
    <form method="post">
        <input type="submit" name="cookies_submit" value="Toggle Display Cookies">
    </form>
    <br>
    <?php
    if ($_SESSION['display_cookies'] == 1) {
        foreach ($_COOKIE as $key=>$val)
        {
            echo $key.' =======> '.$val."<br>\n";
        }
    }
    ?>
    </body>
    </html>

<?php
if (isset($_POST['background_color_submit'])) {
    setcookie('background_color', $_POST['background_color'], time() + 30);
    header('Location: ' . $_SERVER['PHP_SELF']);
}
if (isset($_POST['font_size_submit'])) {
    setcookie('font_size', $_POST['font_size'] . 'pt', time() + 30);
    header('Location: ' . $_SERVER['PHP_SELF']);
}
if (isset($_POST['player_name_submit'])) {
    setcookie('player_name', $_POST['player_name'], time() + 30);
    header('Location: ' . $_SERVER['PHP_SELF']);
}
?>