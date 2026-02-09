<?php
// Script de diagnostic LDAP complet
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Config manuelle pour le test (doit correspondre à Ldap.php)
$host_url = "ldaps.ads-groupe.fr"; // Sans le protocole pour les tests réseaux
$full_url = "ldaps://ldaps.ads-groupe.fr";
$port = 636;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Diagnostic LDAP Avancé</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; max-width: 900px; margin: 0 auto; background: #f1f5f9; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .step { margin-bottom: 1.5rem; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 6px; }
        .success { border-left: 5px solid #22c55e; background: #f0fdf4; }
        .error { border-left: 5px solid #ef4444; background: #fef2f2; }
        .warning { border-left: 5px solid #f59e0b; background: #fffbeb; }
        h3 { margin-top: 0; }
        pre { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 4px; overflow-x: auto; }
        input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; max-width: 300px; }
        button { background: #2563eb; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
<div class="card">
    <h1>Diagnostic Réseau & LDAP</h1>
    
    <div class="step">
        <h3>1. Test de résolution DNS</h3>
        <?php
        $ip = gethostbyname($host_url);
        if ($ip != $host_url) {
            echo "<div class='success'>✅ DNS OK : <strong>$host_url</strong> résout vers <strong>$ip</strong></div>";
        } else {
            echo "<div class='error'>❌ ERREUR DNS : Impossible de résoudre <strong>$host_url</strong>. Vérifiez votre configuration DNS (/etc/resolv.conf).</div>";
        }
        ?>
    </div>

    <div class="step">
        <h3>2. Test de connectivité TCP (Port <?php echo $port; ?>)</h3>
        <?php
        $fp = @fsockopen($host_url, $port, $errno, $errstr, 2);
        if ($fp) {
            echo "<div class='success'>✅ RÉSEAU OK : Connexion TCP établie sur le port $port. Le pare-feu autorise le trafic.</div>";
            fclose($fp);
        } else {
            echo "<div class='error'>❌ ERREUR RÉSEAU : Impossible de se connecter au port $port.<br>Erreur : $errstr ($errno)<br>Causes possibles : Pare-feu bloquant, serveur éteint, ou mauvaise IP.</div>";
        }
        ?>
    </div>

    <div class="step">
        <h3>3. Test d'authentification LDAP</h3>
        <form method="POST">
            <p>Testez vos identifiants réels (ex: administrateur / mot de passe).</p>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <input type="text" name="username" placeholder="Utilisateur (sans @domaine)" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Tester Auth</button>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<div style='margin-top:1rem;'>";
            require_once 'config/Ldap.php';
            
            try {
                $ldap = new Ldap();
                echo "<div>Tentative de connexion via la classe Ldap...</div>";
                
                $startTime = microtime(true);
                $result = $ldap->authenticate($_POST['username'], $_POST['password']);
                $duration = round((microtime(true) - $startTime) * 1000, 2);

                if ($result) {
                    echo "<div class='success' style='margin-top:10px; padding:10px;'>";
                    echo "<strong>✅ AUTHENTIFICATION RÉUSSIE !</strong><br>";
                    echo "Tout fonctionne correctement. Temps : {$duration} ms";
                    echo "</div>";
                } else {
                    echo "<div class='warning' style='margin-top:10px; padding:10px;'>";
                    echo "<strong>⚠️ ACCÈS REFUSÉ</strong><br>";
                    echo "Le serveur est joint, mais le mot de passe est incorrect.";
                    echo "</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error' style='margin-top:10px; padding:10px;'>";
                echo "<strong>❌ EXCEPTION PHP :</strong><br>";
                echo $e->getMessage();
                echo "<br><br><strong>Conseils :</strong>";
                echo "<ul>";
                echo "<li>Si 'Can't contact LDAP server' : Problème SSL souvent. Vérifiez que <code>LDAPTLS_REQCERT=never</code> est bien pris en compte.</li>";
                echo "<li>Si 'Server is unavailable' : Problème réseau temporaire.</li>";
                echo "</ul>";
                echo "</div>";
            }
            echo "</div>";
        }
        ?>
    </div>
    
    <div class="step">
        <h3>Info Système</h3>
        <small>
            PHP Version: <?php echo phpversion(); ?><br>
            LDAP Extension: <?php echo function_exists('ldap_connect') ? 'Installée' : 'Non installée'; ?><br>
            OpenSSL Version: <?php echo OPENSSL_VERSION_TEXT; ?>
        </small>
    </div>
</div>
</body>
</html>
