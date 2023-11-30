<?php
// Récupérer l'ID de l'offre depuis l'URL
if (isset($_GET['id'])) {
    $offre_id = $_GET['id'];

    // Chemin vers le dossier contenant les fichiers PDF
    $folder = __DIR__ . '/uploads';

    // Récupérer la liste des fichiers dans le dossier
    $files = scandir($folder);

    foreach ($files as $file) {
        $pos = strpos($file, '_');
        if ($pos !== false) {
            $digits = substr($file, 0, $pos);
            if ($digits == $offre_id) {
                $file_path = $folder . '/' . $file;

                // Envoi du fichier au navigateur pour le téléchargement
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $file . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                ob_clean();
                flush();
                readfile($file_path);
                exit;
            }
        }
    }
}
?>
