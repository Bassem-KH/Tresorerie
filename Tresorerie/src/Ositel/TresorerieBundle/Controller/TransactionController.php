<?php

namespace Ositel\TresorerieBundle\Controller;

use Ositel\TresorerieBundle\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

//use Swagger\Annotations as SWG;
//use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * Transaction controller.
 *
 */
class TransactionController extends Controller {

    /**
     * @Rest\View(StatusCode = 200,serializerGroups={"TransactionGroup"})
     * @Rest\Get(
     *     path = "/all",
     *     name="All_Transaction",
     *     options={ "method_prefix" = false }
     * )
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $transactions = $em->getRepository('OsitelTresorerieBundle:Transaction')->findAll();
        return array('transactions' => $transactions);
    }

    /**
     * Creates a new transaction entity.
     *
     */
    public function newAction(Request $request) {
        $transaction = new Transaction();
        $form = $this->createForm('Ositel\TresorerieBundle\Form\TransactionType', $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('transaction_show', array('id' => $transaction->getId()));
        }

        return $this->render('transaction/new.html.twig', array(
                    'transaction' => $transaction,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a transaction entity.
     *
     */

    /**
     * @Rest\View(StatusCode = 200,serializerGroups={"TransactionGroup"})
     * @Rest\Get(
     *     path = "/show/{id}",
     *     name="Show_Transaction",
     *     options={ "method_prefix" = false }
     * )
     */
    public function showAction(Transaction $transaction) {
        return $transaction;
    }

    /**
     * Displays a form to edit an existing transaction entity.
     *
     */
    public function editAction(Request $request, Transaction $transaction) {

        $editForm = $this->createForm('Ositel\TresorerieBundle\Form\TransactionType', $transaction);
        $editForm->handleRequest($request);
        $transaction->setUpdatedAt(new \DateTime('now'));
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('transaction_show', array('id' => $transaction->getId()));
        }

        return $this->render('transaction/edit.html.twig', array(
                    'transaction' => $transaction,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a transaction entity.
     *
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Transaction = $em->getRepository('OsitelTresorerieBundle:Transaction')->find($id);
        $em->remove($Transaction);
        $em->flush();
        return $this->redirectToRoute('transaction_index');
    }

    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT t FROM OsitelTresorerieBundle:Transaction t ";

        if ($request->getMethod() == "POST") {
            $title = $request->get('input_title');
            $description = $request->get('input_description');
            $state = $request->get('input_input');
            $valid = $request->get('input_valid');

            if ($title != "" || $description != "" || $state != "" || $valid != "") {
                $dql .= 'where ';
                if ($title != "") {
                    $dql .= 't.title LIKE \'%' . $title . '%\' and ';
                }
                if ($description != "") {
                    $dql .= 't.description LIKE \'%' . $description . '%\' and ';
                }
                if ($state != "") {
                    $dql .= 't.isInput =' . $state . ' and ';
                }
                if ($valid != "") {
                    $dql .= 't.isValid =' . $valid . ' and ';
                }
                $dql = substr($dql, 0, strlen($dql) - 4);
            }
        }

        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('transaction/search.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Rest\View(StatusCode = 200,serializerGroups={"TransactionGroup"})
     * @Rest\Get(
     *     path = "/month",
     *     name="by_month_Transaction",
     *     options={ "method_prefix" = false }
     * )
     */
    public function monthAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = $now->format('Y');
        if ($request->getMethod() == "POST") {
            $input_month = $request->get('input_month');
            if ($input_month != "") {
                $month = substr($input_month, 5, 2);
                $year = substr($input_month, 0, 4);
            }
        }


        // calcul de la somme des inputs du mois et de la somme des outputs du mois
        $somme_input = $this->container->get('Ositel_Tresorerie.somme')->somme($month, $year, $em, 1);
        $somme_output = $this->container->get('Ositel_Tresorerie.somme')->somme($month, $year, $em, 0);

        // Calcul Tresorerie au début du mois
        $somme_debut_mois_input = $this->container->get('Ositel_Tresorerie.somme')->somme_debut_mois($month, $year, $em, 1);
        $somme_debut_mois_output = $this->container->get('Ositel_Tresorerie.somme')->somme_debut_mois($month, $year, $em, 0);
        $tresorerie_debut = $somme_debut_mois_input['somme'] - $somme_debut_mois_output['somme'];

        // Calcul Tresorerie à la fin du mois
        $somme_fin_mois_input = $this->container->get('Ositel_Tresorerie.somme')->somme_fin_mois($month, $year, $em, 1);
        $somme_fin_mois_output = $this->container->get('Ositel_Tresorerie.somme')->somme_fin_mois($month, $year, $em, 0);
        $tresorerie_fin = $somme_fin_mois_input['somme'] - $somme_fin_mois_output['somme'];

        //liste des transactions valides du mois
        $dql_list_transaction = "SELECT t FROM OsitelTresorerieBundle:Transaction t where t.isValid=1 and (MONTH(t.createdAt) = " . $month . " and YEAR(t.createdAt) = " . $year . ")";
        $query = $em->createQuery($dql_list_transaction);
        $transactions = $query->getResult();

        return array('transactions' => $transactions,
            'somme_input' => $somme_input['somme'],
            'somme_output' => $somme_output['somme'],
            'Year' => $year,
            'Month' => $month,
            'tresorerie_debut' => $tresorerie_debut,
            'tresorerie_fin' => $tresorerie_fin,
        );
    }

}
