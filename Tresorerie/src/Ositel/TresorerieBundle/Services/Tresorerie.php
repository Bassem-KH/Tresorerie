<?php

namespace Ositel\TresorerieBundle\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tresorerie
 *
 * @author Dell
 */
class Tresorerie {

   
    

    public function somme($month, $year, $em, $in_out) {

        $dql_somme = "SELECT SUM (t.amount) as somme FROM OsitelTresorerieBundle:Transaction t where (t.isValid=1 and (MONTH(t.createdAt) = " . $month . " and YEAR(t.createdAt) = " . $year . ") and t.isInput=".$in_out.")";
        $query_somme = $em->createQuery($dql_somme);
        $somme = $query_somme->getSingleResult();
        //$result=$somme_input['somme'];
        return $somme;
    }
   public function somme_debut_mois($month, $year, $em, $in_out){
        $dql_somme = "SELECT SUM (t.amount) as somme FROM OsitelTresorerieBundle:Transaction t where (t.isValid=1 and t.isInput=".$in_out." and "
                ."(YEAR(t.createdAt)<".$year." or (YEAR(t.createdAt)=".$year." and MONTH(t.createdAt)<".$month.")))";
        $query_somme = $em->createQuery($dql_somme);
        $somme = $query_somme->getSingleResult();
        //$result=$somme_input['somme'];
        return $somme;       
   }
   public function somme_fin_mois($month, $year, $em, $in_out){
        $dql_somme = "SELECT SUM (t.amount) as somme FROM OsitelTresorerieBundle:Transaction t where (t.isValid=1 and t.isInput=".$in_out." and "
                ."(YEAR(t.createdAt)<".$year." or (YEAR(t.createdAt)=".$year." and MONTH(t.createdAt)<=".$month.")))";
        $query_somme = $em->createQuery($dql_somme);
        $somme = $query_somme->getSingleResult();
        //$result=$somme_input['somme'];
        return $somme;       
   }

}

//put your code here

