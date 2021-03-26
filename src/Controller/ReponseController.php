<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\ReponseList;
use App\Form\ReponseType;
use App\Form\ReponseListType;

use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="reponse_index", methods={"GET"})
     */
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reponse_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //this reponseList will countain all of our reponses
        $reponseList = new ReponseList();

        //Get question id from the url
        $question = $this->getDoctrine()->getRepository(Question::class)->find($request->query->get("ques_id"));

        // create new rponses and add their question id
        // then add them to a the reopnseList
        for ($x = 0; $x < $question->getNombRep(); $x++) {
            $reponse = new Reponse();
            $reponse->setIdQues($question);
            $reponseList->addReponse($reponse);
        }


        //resopnseListType is a new form which contains a list of field for the responses
        $form = $this->createForm(ReponseListType::class, $reponseList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //get the reponsnes from the reponseList's form and them one by one to our database
            foreach ($reponseList->getReponses() as $rep)
                $entityManager->persist($rep);
            $entityManager->flush();
            $nb_question = (int)$request->query->get("nb_question");

            // redirect to the user to a page where he chooses the right reponse
            return $this->redirectToRoute('select_reponse', ['ques_id' => $question->getId(), "nb_question" => $nb_question]);

        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponseList,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/select", name="select_reponse", methods={"GET", "POST"})
     */
    public function select(Request $request): Response
    {

        //Get the question from the url
        $question = $this->getDoctrine()->getRepository(Question::class)->find($request->query->get("ques_id"));

        //get the lists of reponses belong to this question
        $reponses = $this->getDoctrine()->getRepository(Reponse::class)->findBy(['id_ques' => $question]);

        //add all this question to a list (key => value)
        $list = array();
        foreach ($reponses as $rep)
            $list[$rep->getContenuRep()] = $rep->getId();


        //Create our form which contains one choiceType that contains the list of reponses
        $form = $this->createFormBuilder($reponses)
            ->add('reponses', ChoiceType::class, ['choices' => $list])
            ->getForm();
        $form->handleRequest($request);
        $nb_question = (int)$request->query->get("nb_question");

        if ($form->isSubmitted() && $form->isValid()) {

            //get the selected reponse
            $reponse = $this->getDoctrine()->getRepository(Reponse::class)->find($form["reponses"]->getData());

            //add the selected to the question as a RepJust and updating the database
            $question->setRepJust($reponse);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            //checking if we need to add more question or we finished based on the number of question
            //declared in the table quiz
            if ($nb_question > 1)

                //if we have to add more question we redirect the user to a form for new question
                return $this->redirectToRoute('question_new', ['id_quiz' => $question->getQuizId()->getId(), "nb_question" => $nb_question - 1]);
            else
                //else we redirect him to another page
                return $this->render('quiz/showQuiz.html.twig', [
                    'quiz' => $question->getQuizId(),
                ]);

        }


        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponses,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/add", name="reponse_add", methods={"GET","POST"} )
     */
    public function add(Request $request): Response
    {
        //Get question id from the url
        $question = $this->getDoctrine()->getRepository(Question::class)->find($request->query->get("ques_id"));

        $reponse = new Reponse();

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //increment number of reponse by 1
            $question->setNombRep($question->getNombRep() + 1);
            $reponse->setIdQues($question);
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_show', ['id' => $question->getQuizId()->getId()]);

        }
        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="reponse_delete", methods={"GET"})
     */
    public function delete(Reponse $reponse): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $question = $this->getDoctrine()->getRepository(Question::class)->find($reponse->getIdQues()->getId());
        $question->setNombRep($question->getNombRep() - 1);
        $entityManager->remove($reponse);
        $entityManager->flush();

        return $this->redirectToRoute('quiz_show', ['id' => $question->getQuizId()->getId()]);
    }
    /**
     * @Route("/{id}", name="reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reponse $reponse): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();


            return $this->render('quiz/showQuiz.html.twig', [
                'quiz' => $reponse->getIdQues()->getQuizId(),
            ]);
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/changeRepCorr/{id}", name="reponse_correct", methods={"GET","POST"})
     */
    public function correct(Reponse $reponse): Response
    {
        $question = $reponse->getIdQues();
        $question->setRepJust($reponse);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('quiz_show', ['id' => $question->getQuizId()->getId()]);

    }


}



