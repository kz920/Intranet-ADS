<?php
class Ldap {
    // Configuration
    private $ad_host = "ldaps://ldaps.ads-groupe.fr"; 
    // Note : Le port 636 est implicite avec ldaps://, mais on le garde pour info ou usages spécifiques
    private $ldap_dn_suffix = "@ads.fr"; 

    public function authenticate($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }

        if (!function_exists('ldap_connect')) {
            throw new Exception("L'extension PHP LDAP n'est pas installée.");
        }

        // --- GESTION SSL ---
        // Pour éviter les erreurs "Can't contact LDAP server" dues aux certificats auto-signés
        // On configure le contexte global AVANT la connexion
        if (!defined('LDAP_OPT_X_TLS_REQUIRE_CERT')) {
             define('LDAP_OPT_X_TLS_REQUIRE_CERT', 0x6006);
        }
        if (!defined('LDAP_OPT_X_TLS_NEVER')) {
             define('LDAP_OPT_X_TLS_NEVER', 0);
        }
        
        // Tente de configurer globalement (fonctionne mieux sur certaines versions de PHP/OpenLDAP)
        ldap_set_option(NULL, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);
        putenv('LDAPTLS_REQCERT=never');

        // --- CONNEXION ---
        // ATTENTION : Avec une URI (ldaps://), on NE PASSE PAS le port en 2eme argument, sinon ça peut échouer.
        $ldap_conn = ldap_connect($this->ad_host);
        
        if (!$ldap_conn) {
            throw new Exception("Impossible d'initialiser la connexion vers " . $this->ad_host);
        }

        // Options obligatoires AD
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ldap_conn, LDAP_OPT_NETWORK_TIMEOUT, 5);

        // --- AUTHENTIFICATION ---
        $ldap_user = $username . $this->ldap_dn_suffix;

        // Le @ supprime l'erreur PHP brute, on gère l'erreur proprement après
        $bind = @ldap_bind($ldap_conn, $ldap_user, $password);

        if ($bind) {
            @ldap_unbind($ldap_conn);
            return true;
        } else {
            // Diagnostic précis de l'erreur
            $error_code = ldap_errno($ldap_conn);
            $error_msg = ldap_error($ldap_conn);
            // Si possible, obtenir le détail étendu (utile pour AD)
            $diagnostic_msg = "";
            if (ldap_get_option($ldap_conn, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
                $diagnostic_msg = " - Detail AD: $extended_error";
            }
            
            @ldap_unbind($ldap_conn);

            // 49 = Invalid Credentials (normal si mauvais mot de passe)
            if ($error_code !== 0 && $error_code !== 49) {
                // Erreur technique (Réseau, Certificat, Compte verrouillé...)
                throw new Exception("Erreur LDAP ($error_code): $error_msg" . $diagnostic_msg);
            }

            return false;
        }
    }
}
