<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Styles/user_bp.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  
  
 
     <title>Rebooters Search</title>
</head>
<body>

    <nav>
        <div class="logo">
            <img src="Images/logo.svg" alt="REBOOTERS Logo" height="200" > <!-- Ajustez le chemin et la taille -->
           
        </div>
       
        <div>
           
            <div class="user-menu">
                <button class="menu-button">USER</button>
              <button class="user-button">☰</button>
              <div class="user-dropdown">
                <a href="./user_bp.html">Paramètres</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
              </div>
            </div>
          </div>
    </nav>
    <body>

        <div class="container">
          <div class="header">
            <h1>Gestion des bonnes pratiques</h1>
          </div>
        
          <form>
            <div class="form-group">
              <label for="keyword">Mot clé</label>
              <input type="text" id="keyword" placeholder="Entrez un mot clé">
            </div>
        
            <div class="form-group">
              <label for="good-practice">Bonne pratique</label>
              <input type="text" id="good-practice" placeholder="Entrez une bonne pratique">
            </div>
        
            <div class="form-group">
              <label for="program">Programme</label>
              <select id="program">
                <option value="PROG_1">PROG_1</option>
                <option value="PROG_2">PROG_2</option>
                <option value="GENERIC">GENERIQUE</option>
              </select>
            </div>
        
            <div class="checkbox-group">
              <label>
                <input type="checkbox" name="phase" value="Codage">
                Codage
              </label>
              <label>
                <input type="checkbox" name="phase" value="Analyse">
                Analyse
              </label>
              <label>
                <input type="checkbox" name="phase" value="Execution">
                Execution
              </label>
              <label>
                <input type="checkbox" name="phase" value="Codage">
                Préparation
              </label>
            </div>
        
            <button type="submit" class="btn">Ajouter</button>
          </form>
        </div>
        
    
    