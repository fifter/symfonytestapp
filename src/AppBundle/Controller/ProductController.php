<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;

class ProductController extends Controller
{
    /**
     * @Route("/products", name="ProductsPage")
     */
    public function indexAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }

        $sort = $request->request->get('sort');
        $sort_type = $request->request->get('current_sort_type');
        $name = $request->request->get('name');

        if($sort_type === $sort . '_' . 'ASC')
            $sort_type = 'DESC';
        else
            $sort_type = 'ASC';

        if(!$sort)
            $sort = 'name';

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $products = $this->getDoctrine()->getRepository(Product::class)
                ->createQueryBuilder('p')
                ->where('p.user = :user')
                ->andWhere('p.name LIKE :name')
                ->orderBy('p.' . $sort, $sort_type)
                ->setParameter('user', $this->get('security.token_storage')->getToken()->getUser())
                ->setParameter('name', '%' . $name . '%')
                ->getQuery()
                ->getResult();
        } else {
            $products = $this->getDoctrine()->getRepository(Product::class)
                ->createQueryBuilder('p')
                ->join('p.user', 'u')
                ->where('p.name LIKE :name')
                ->orderBy('p.' . $sort, $sort_type)
                ->setParameter('name', '%' . $name . '%')
                ->getQuery()
                ->getResult();
        }

        return $this->render('products/index.html.twig', array(
            'products' => $products,
            'sort_type' => $sort . '_' . $sort_type,
            'name_filter' => $name
        ));
    }
    /**
     * @Route("/products/add", name="AddProduct")
     */
    public function addProductAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $product->getImage();

            if($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    'images/',
                    $fileName
                );

                $product->setImage($fileName);
            }
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                $product->setUser($this->get('security.token_storage')->getToken()->getUser());
            }

            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            return $this->redirect($this->generateUrl('ProductsPage'));
        }

        return $this->render('products/new.html.twig', array(
            'form' => $form->createView(),
            'action' => 'AddProduct'
        ));
    }
    /**
     * @Route("/products/update/{id}", name="UpdateProduct", requirements={"id": "\d+"})
     */
    public function updateProductAction($id, Request $request)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)
            ->findOneBy(['id' => $id]);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $product->getImage();

            if($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    'images/',
                    $fileName
                );

                $product->setImage($fileName);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('ProductsPage'));
        }

        return $this->render('products/new.html.twig', array(
            'form' => $form->createView(),
            'action' => 'UpdateProduct',
            'id' => $id
        ));
    }
    /**
     * @Route("/products/delete", name="DeleteProduct")
     */
    public function deleteProductAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw $this->createAccessDeniedException();
        }


        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->request->get('product_id'));

        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateUrl('ProductsPage'));
    }
}
