<?php
/**
 * User: vaso
 * Date: 23.03.15
 * Time: 0:26
 */
$_ = $this->get('_');
?>

<div class="container">
    <div class="row" id="info-page">
        <div class="col-xs-20 col-md-10"><h2>Логин</h2>

            <form method="POST" autocorrect="off" autocapitalize="off">
                <label for="username">Имя пользователя или email:</label>
                <input class="input" name="username" type="text" id="username">
                <label for="password">Пароль:</label>
                <input class="input" name="password" type="password" value="" id="password">
                <span class="just-link">
                    <a href="/register">Создать аккаунт</a>
                    <a href="/forgot-password">Забыли пароль?</a>
                </span>

                <div class="send-btn">
                    <input class="button-1-download-big" type="submit" value="<?php echo $_['Do login'] ?>">
                </div>

            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>

