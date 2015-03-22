<?php
/**
 * User: vaso
 * Date: 23.03.15
 * Time: 1:32
 */
$_ = $this->get('_');
?>

<div class="container">
    <div class="row" id="info-page">
        <div class="col-xs-20 col-md-10"><h2>Логин</h2>

            <form method="POST" autocorrect="off" autocapitalize="off">
                <label for="username">Имя пользователя или email:</label>
                <input class="input" name="username" type="text" id="username">

                <label for="email">E-Mail:</label>
                <input class="input" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="off" name="email" type="email" id="email">

                <label for="password">Пароль:</label>
                <input class="input" name="password" type="password" value="" id="password">


                <div class="send-btn">
                    <input class="button-1-download-big" type="submit" value="<?php echo $_['Do register'] ?>">
                </div>

            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>