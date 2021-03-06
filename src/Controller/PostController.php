<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="app_post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_post_new", methods={"GET", "POST"})
     */
    public function new(
        Request $request,
        PostRepository $postRepository
    ): Response {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($request);
            //var_dump($request->files->get('image'));
            //$image = $request->files->get('post[image]');
            // $image_name = $request->post[image];
            /* $image = $request->file('image');
            if ($image != '') {
                echo '111';
            } else {
                echo 'viiide';
            }*/

            $image = $form['image']->getData();

            if ($image) {
                $image_name = $image->getClientOriginalName();

                //$image = $request->files->get('image');
                //$image_name = $image->getClientOriginalName();
                //$image_name = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $image_name
                );
                $post->setImage($image_name);
            }
            $postRepository->add($post);
            return $this->redirectToRoute(
                'app_post_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_post_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request $request,
        Post $post,
        PostRepository $postRepository
    ): Response {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();

            if ($image) {
                $image_name = $image->getClientOriginalName();
                $image->move(
                    $this->getParameter('image_directory'),
                    $image_name
                );
                $post->setImage($image_name);
            }

            $postRepository->add($post);
            return $this->redirectToRoute(
                'app_post_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_post_delete")
     */
    public function delete(
        Request $request,
        Post $post,
        PostRepository $postRepository
    ): Response {
        echo 'ok';
        if (
            $this->isCsrfTokenValid(
                'delete' . $post->getId(),
                $request->request->get('_token')
            )
        ) {
            $postRepository->remove($post);
        }

        return $this->redirectToRoute(
            'app_post_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    /**
     * @Route("/delete/{id}", name="app_post_delete2")
     */
    public function delete2(
        Request $request,
        Post $post,
        PostRepository $postRepository
    ): Response {
        echo 'ok';

        $postRepository->remove($post);

        return $this->redirectToRoute(
            'app_post_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}
