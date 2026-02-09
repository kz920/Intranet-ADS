<?php
class Ldap {
    // À configurer selon votre infrastructure AD
    private $ad_host = "ldap://192.168.1.100"; // IP ou nom DNS du contrôleur de domaine
    private $ad_domain = "ads.local"; // Votre nom de domaine AD
    private $ad_port = 389; // 389 pour LDAP standard, 636 pour LDAPS
    private $ldap_dn_suffix = "@ads.local"; // Suffixe pour le login (User Principal Name)

    public function authenticate($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }

        // Vérification de l'extension PHP LDAP
        if (!function_exists('ldap_connect')) {
            throw new Exception("L'extension PHP LDAP n'est pas installée sur le serveur.");
        }

        // 1. Connexion au serveur
        $ldap_conn = ldap_connect($this->ad_host, $this->ad_port);
        
        if (!$ldap_conn) {
            throw new Exception("Impossible de contacter le serveur LDAP.");
        }

        // Options requises pour Active Directory
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

        // 2. Tentative de Bind (Authentification)
        // Format typique AD : utilisateur@domaine.local ou DOMAINE\utilisateur
        $ldap_user = $username . $this->ldap_dn_suffix;

        try {
            // Le @ supprime les warnings PHP si le login échoue (c'est normal en cas de mauvais mot de passe)
            $bind = @ldap_bind($ldap_conn, $ldap_user, $password);

            if ($bind) {
                // Authentification réussie !
                
                // Optionnel : Récupérer les infos de l'utilisateur (Nom, Prénom, Mail, Groupes)
                // $filter = "(sAMAccountName=$username)";
                // $result = ldap_search($ldap_conn, "DC=ads,DC=local", $filter);
                // $info = ldap_get_entries($ldap_conn, $result);
                
                ldap_unbind($ldap_conn);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
