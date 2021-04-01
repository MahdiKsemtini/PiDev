<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Freelancer;
use App\Entity\PostLike;
use App\Entity\Publications;
use App\Form\CommentairesType;
use App\Form\PublicationsType;
use App\Repository\FreelancerRepository;
use App\Repository\SocieteRepository;
use App\Repository\PostLikeRepository;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;



class PublicationsController extends AbstractController
{
    /**
     * @Route("/forum",name="forum")
     */
    public function index(Request $request, PaginatorInterface $paginator, FreelancerRepository $freelancerRepository, SocieteRepository  $societeRepository): Response
    {
        $session = $request->getSession();
        if($session->get('id')==null){
            return $this->redirectToRoute('SignIn');
        }else{
            $l=$this->getDoctrine()->getRepository(PostLike::class);
            $like=$l->findAll();
            $p=$this->getDoctrine()->getRepository(Publications::class);
            $pub=$p->findAll();
            if ($request->isMethod("POST"))
            {
                $keyword = $request->get("keyword");
                $pub=$p->findByKey($keyword);
            }



            $pubs = $paginator->paginate($pub, $request->query->getInt('page', 1), 3);
            $session = $request->getSession();
            $publication = new Publications();
            $publication->setDatePublication(new \DateTime('now'));



            $form = $this->createForm(PublicationsType::class, $publication);
            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                $file = $form['image']->getData();
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$filename);
                $publication->setImage($filename);
                if($session->get('compte_facebook')!=null){
                    $freelancer=$freelancerRepository->find($session->get('id'));
                    $publication->setFreelancer($freelancer);
                }else{
                    $societe=$societeRepository->find($session->get('id'));
                    $publication->setSociete($societe);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($publication);
                $em->flush();


                return $this->redirect('forum');
            }

            return $this->render('Front/publications/forum.html.twig', array('publications'=>$pubs,'likes'=>$like,'f' => $form->createView()));
        }

    }

    /**
     * @Route("/publicationsBack",name="publicationsBack")
     *
     */
    public function list_publications(Request $request, PaginatorInterface $paginator): Response
    {
        $p=$this->getDoctrine()->getRepository(Publications::class);
        $publications=$p->findAll();



        if ($request->isMethod("POST"))
        {
            $publications=$p->trierdatep();
        }

        return $this->render('Back/publications/publicationsBack.html.twig', array('publications'=>$publications));
    }




    /**
     * @Route("/supprimerPublication/{id}",name="supprimerPublication")
     */
    public function supprimerPublication($id){
        $em=$this->getDoctrine()->getManager();
        $publications=$em->getRepository(Publications::class)->find($id);


        $em->remove($publications);
        $em->flush();
        return $this->redirectToRoute('forum');

    }

    /**
     * @Route("/deletePublication/{id}",name="deletePublication")
     */
    public function deletePublication($id){
        $em=$this->getDoctrine()->getManager();
        $publications=$em->getRepository(Publications::class)->find($id);


        $em->remove($publications);
        $em->flush();
        return $this->redirectToRoute('publicationsBack');

    }



    /**
     * @Route("/forumedit{id}",name="modifierpublication")
     */
    public function modifierPublication(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $publications = $this->getDoctrine()->getRepository(Publications::class)->find($id);
        $publications->setImage(null);

        $form = $this->createForm(PublicationsType::class, $publications);

        $form = $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $file = $form['image']->getData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $publications->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($publications);
            $em->flush();
            return $this->redirectToRoute('forum');
        }
        return $this->render('Front/publications/forumedit.html.twig', array('f' => $form->createView()));

    }



    /**
     * @Route("/post/{id}/like",name="post_like")
     *
     */
    public function like(Request $request, Publications $post ,FreelancerRepository $freelancerRepository, PostLikeRepository $likeRepo): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $freelancer=$freelancerRepository->find($session->get('id'));
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(Freelancer::class)->find($freelancer);
        if($post->isLikedByUser($user)){
            $like=$likeRepo->findOneBy([
                'post'=>$post,
                'user'=>$user
            ]);
            $manager->remove($like);
            $manager->flush();
            return $this->json([
                'code'=>200,
                'message'=>'like supprimé',
                'likes'=> $likeRepo->count(['post'=>$post])
            ],200);

        }

        $like= new PostLike();
        $like->setPost($post)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code'=>200,
            'message' => 'like ajouté',
            'likes'=>$likeRepo->count(['post'=>$post])

        ],200);


    }
}