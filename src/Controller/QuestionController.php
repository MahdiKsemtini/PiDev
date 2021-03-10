<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        $quiz = $this->getDoctrine()->getRepository(Quiz::class)->find($request->query->get("id_quiz"));
        $question->setQuizId($quiz);
        $nb_question = (int) $request->query->get("nb_question");
        if ($form->isSubmitted() && $form->isValid()) {

            if($nb_question == -1){
                $quiz->setNombQuestion($quiz->getNombQuestion()+1);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('reponse_new', ['ques_id' => $question->getId(), "nb_question" => $nb_question ]);

        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"GET"})
     */
    public function delete(Question $question): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $quiz = $question->getQuizId();
        $quiz->setNombQuestion($quiz->getNombQuestion() -1);
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('quiz_show', ['id' => $quiz->getId()]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->remove("nomb_rep");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->render('quiz/showQuiz.html.twig', [
                'quiz' => $question->getQuizId(),
            ]);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }


}
