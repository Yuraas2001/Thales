<?php
// Chemin vers le script Python
$pythonScript = 'export_to_pdf.py';

// Exécution du script Python
$output = shell_exec("python3 $pythonScript 2>&1");

// Affichage de la sortie pour le débogage
echo "<pre>$output</pre>";

// Rediriger vers la page précédente ou afficher un message de succès
header('Location: admin_home.php?export=success');
exit;
?>
