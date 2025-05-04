<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TagType;
use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class TagController extends AbstractController
{
    #[Route('/tag/create', name: 'tag_create', methods: 'GET|POST')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();
            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/tags', name: 'tag_index', methods: 'GET')]
    public function all(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tag/{id}', name: 'tag_show', methods: 'GET')]
    public function show(Tag $tag): Response
    {
        if (!$tag) {
            throw $this->createNotFoundException('Tag nie został znaleziony.');
        }

        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }





    #[Route('/tag/{id}/edit', name: 'tag_edit', methods: 'GET|POST')]
    public function edit(int $id, TagRepository $tagRepository, Request $request, EntityManagerInterface $em): Response
    {

        $tag = $tagRepository->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('Tag nie został znaleziony.');
        }


        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                return $this->redirectToRoute('tag_index');
            }
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);

    }

    #[Route('/tag/{id}/delete', name: 'tag_delete', methods: 'GET|POST')]
    public function delete(int $id, TagRepository $tagRepository, Request $request, EntityManagerInterface $em): Response
    {

        $tag = $tagRepository->find($id);

        if (!$tag) {
            throw $this->createNotFoundException('Produkt nie został znaleziony.');
        }
        if ($request->isMethod('POST')) {
            $em->remove($tag);
            $em->flush();
            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/delete.html.twig', [
            'tag' => $tag,
        ]);

    }
}
