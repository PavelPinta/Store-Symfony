<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller {

    public function showFormAction($type, $id, $action) {

        if ($type == 'category') {
            $categories = null;
            if ($action == 'edit') {
                $item = $this->getDoctrine()->getRepository('StoreBundle:Category')->find($id);
            }
        } else if ($type == 'product') {
            $categories = $this->getDoctrine()->getRepository('StoreBundle:Category')->findAll();
            if ($action == 'edit') {
                $item = $this->getDoctrine()->getRepository('StoreBundle:Product')->find($id);
            }
        } else {
            return $this->render('StoreBundle::error404.html.twig');
        }

        if ($action != "edit") {
            $item = null;
        }

        return $this->render('StoreBundle:Admin:edit.html.twig', array(
                    'type' => $type,
                    'categories' => $categories,
                    'item' => $item,
                    'action' => $action,
                    'message' => null
        ));
    }

    public function processingFormAction() {

        if (!empty($_POST['submit']) && ($_POST['type'] == 'product' || $_POST['type'] == 'category')) {

            $em = $this->getDoctrine()->getManager();
            if ($_POST['type'] == 'product') {
                $id = intval($_POST['category']);
                $category = $this->getDoctrine()->getRepository('StoreBundle:Category')->find($id);
                if ($_POST['submit'] == 'Add') {
                    $c = new \StoreBundle\Entity\Product();
                } else {
                    $c = $em->getRepository('StoreBundle:Product')->find(intval($_POST['id']));
                }
                $c->setCategory($category);
            } else {
                if ($_POST['submit'] == 'Add') {
                    $c = new \StoreBundle\Entity\Category();
                } else {
                    $c = $em->getRepository('StoreBundle:Category')->find(intval($_POST['id']));
                }
            }
            $c->setName(strval($_POST['name']));
            if ($_POST['submit'] == 'Remove') {
                $em->remove($c);
            } else {
                $em->persist($c);
            }
            $em->flush();
            return $this->redirect('/');
        } else if (!empty($_GET['id'])) {
            $em = $this->getDoctrine()->getManager();
            $c = $em->getRepository('StoreBundle:Product');
        } else {
            return $this->render('StoreBundle::error404.html.twig');
        }
    }

}
