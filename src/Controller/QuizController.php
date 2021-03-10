<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Reponse;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/", name="quiz_index", methods={"GET"})
     */
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="quiz_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('question_new', ['id_quiz' => $quiz->getId(), "nb_question" => $quiz->getNombQuestion()]);
        }

        return $this->render('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="quiz_show", methods={"GET"})
     */
    public function show(Quiz $quiz): Response
    {
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(["quiz_id" => $quiz->getId()]);



        foreach($questions as $question){

            $reponses = $this->getDoctrine()->getRepository(Reponse::class)->findBy(["id_ques" => $question->getId()]);
            foreach( $reponses as $reponse)
                $question->addReponse($reponse);
            $quiz->addQuestion($question);

        }


        return $this->render('quiz/showQuiz.html.twig', [
            'quiz' => $quiz,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="quiz_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quiz $quiz): Response
    {

        //get the old reponse number
        $nb_quiz = $quiz->getNomQuiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->remove('nomb_question');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('quiz_show', ['id' => $quiz->getId()]);


        }

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Quiz $quiz): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quiz);
            $entityManager->flush();


        return $this->render('home/index.html.twig');
    }
}


