<?php

/**
 * Description of getTypeUser : 
 * Récupératon du type utilisateur pour l'afficher. 
 * Je fais cela car, le champ "roles" c'est un tableau et j'ai pass envie de remettre les tests dans le code à chaque fois pour récupérer
 * 
 * @author brice_000
 */

namespace Application\UsersBundle\Services;

use Application\UsersBundle\Entity\Users;

class getTypeUser {
    
    /*public function __construct($user)
    {
        $this->user = $user;
    }*/
    
    public function typeUser(Users $user)
    {
        $roles = $user->getRoles();    
        foreach($roles as $role)
        {
            switch ($role){
                case "ROLE_CONSULTANT":
                    return "Consultant";
                    break;
                case "ROLE_GESTION":
                    return "Gestionnaire administratif";
                    break;
                case "ROLE_COMMERCIAL":
                    return "Commercial téléphonique";
                    break;
                case "ROLE_ADMIN":
                    return "Administrateur";
                    break;
            }
        }
    }
}
