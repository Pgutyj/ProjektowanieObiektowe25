<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'product_create', methods: 'GET|POST')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/products', name: 'product_index', methods: 'GET')]
    public function all(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'product_show', methods: 'GET')]
    public function show(Product $product): Response
    {
        if (!$product) {
            throw $this->createNotFoundException('Produkt nie został znaleziony.');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }





    #[Route('/product/{id}/edit', name: 'product_edit', methods: 'GET|POST')]
    public function edit(int $id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em): Response
    {

        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produkt nie został znaleziony.');
        }


        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                return $this->redirectToRoute('product_index');
            }
        }

            return $this->render('product/edit.html.twig', [
                'form' => $form->createView(),
                'product' => $product,
            ]);

    }

    #[Route('/product/{id}/delete', name: 'product_delete', methods: 'GET|POST')]
    public function delete(int $id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em): Response
    {

        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produkt nie został znaleziony.');
        }
        if ($request->isMethod('POST')) {
            $em->remove($product);
            $em->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/delete.html.twig', [
            'product' => $product,
        ]);

    }
}
