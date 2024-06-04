<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Styles/login_styles.css">
    <title>Connexion</title>
</head>

    <body>
        <div class="logo-text-container">
            <img src="/Images/logo.svg" alt="Logo REBOOTERS" class="logo" />
            
        </div>
        <?php if ($_GET['err'] == 1)  {?>
        <p style="color: red">Identifiant ou mot de passe incorrect. Veuillez recommencer. </p>
        <?php } ?>
        <?php if ($_GET['err'] == 2)  {?>
        <p style="color: red">Votre compte a été bloqué, contactez un Administrateur pour le déverouiller  </p>
        <?php } ?>
        <div class="login-container">
            <h2>Identifiez-vous</h2>
            <form method="POST" action="/Database/login.php">
                <div class="form-group">
                    <label for="username">Identifiant</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="Login-bouton">Se connecter</button>
                <div class="login-footer">
                    <a href="#">Mot de passe oublié ?</a>
                </div>
            </form>
        </div>
            </form>
        </div>
    </body>
    </html>