<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller {

    public function showProductsAction($name = null) {
        $limit = 15;
        $page = empty($_GET['page']) ? 1 : $_GET['page'];
        // userdata
        $user = $this->get('security.context')->getToken()->getUser();
        $user = is_object($user) ? $user->getUsername() : null;
        // get categories
        $categories = $this->getCategories();
        // get products
        $products = $this->getProducts($name, $page, $limit);

        $pages = ceil(count($products) / $limit);

        return $this->render('StoreBundle:Shop:list.html.twig', array(
                    'categories' => $categories,
                    'products' => $products['products'],
                    'user' => $user,
                    'pages' => $products['pages']
        ));
    }

    /**
     * 
     * @param int $limit
     * @return int
     */
    private function getPages($limit) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT count(p) FROM StoreBundle:Product p');
        $count = $query->getResult()[0][1];
        $pages = ceil($count / $limit);
        return $pages;
    }

    /**
     * 
     * @return array
     */
    private function getCategories() {
        return $this->getDoctrine()->getRepository('StoreBundle:Category')->findAll();
    }

    /**
     * 
     * @param string $name
     * @param int $page
     * @param int $limit
     * @return array
     */
    private function getProducts($name, $page, $limit) {
        $offset = ($page - 1) * $limit;
        $em = $this->getDoctrine()->getManager();
        if ($name == null) {
            $products = $em->getRepository('StoreBundle:Product')->findBy(array(), array('name' => 'ASC'), $limit, $offset);
            $query = $em->createQuery('SELECT count(p) FROM StoreBundle:Product p');
            $count = $query->getResult()[0][1];
            $pages = ceil($count / $limit);
        } else {
            $categoryId = $em->getRepository('StoreBundle:Category')->findBy(['name' => $name]);
            $products = $em->getRepository('StoreBundle:Product')
                    ->findBy(['category' => $categoryId[0]->getId()], array('name' => 'ASC'), $limit, $offset);
            $query = $em->createQuery('SELECT count(p) FROM StoreBundle:Product p WHERE p.category = :category')
                    ->setParameter('category', $categoryId);
            $count = $query->getResult()[0][1];
            $pages = ceil($count / $limit);
        }
        return array('products' => $products, 'pages' => $pages);
    }

}
