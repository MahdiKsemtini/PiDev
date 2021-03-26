<?php

namespace App\Controller;

use App\Entity\ListReponsesCondidat;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Reponse;
use App\Entity\ReponseCondidat;
use App\Entity\ReponseList;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TakeQuizController extends AbstractController
{
    /**
     * @Route("/take/quiz", name="take_quiz")
     */
    public function index(): Response
    {
        return $this->render('take_quiz/index.html.twig', [
            'controller_name' => 'TakeQuizController',
        ]);
    }


    /**
     * @Route("/take/{id}", name="quiz_take", methods={"GET", "POST"})
     */
    public function take(Request $request, Quiz $quiz): Response
    {
        //create a new list of questions
        $reponseList = new ListReponsesCondidat();

        //set its quiz
        $reponseList->setQuiz($quiz);
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(["quiz_id" => $quiz->getId()]);
        $formBuilder = $this->createFormBuilder($questions);
        $i = 0;

        foreach($questions as $question){
            $i++;
            //add every question in the quiz to the new form
            $formBuilder->add("Question".$i, TextType::class, [ 'data' => $question->getContenuQues(),'label' =>' Question '.$i, 'disabled' => true]);
            //list that will contains the reponses list  of a question
            $list= [];
            $reponses = $this->getDoctrine()->getRepository(Reponse::class)->findBy(["id_ques" => $question->getId()]);
            foreach( $reponses as $reponse){
                $question->addReponse($reponse);
                //add every reponse to the list
                $list[$reponse->getContenuRep()] = $reponse->getId();
            }
            // add the list of reponse to the form then do the same for the next question
            $formBuilder->add('reponses'.$i, ChoiceType::class, ['choices' => $list,'label' =>'  ', 'data' => reset($list) ,'multiple'=>false, 'expanded' => true]);
            $quiz->addQuestion($question);

        }
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        $reponseCondidat = new ReponseCondidat();
        if ($form->isSubmitted() && $form->isValid()) {
            $i =0 ;

            //list will countains our ReponseCondidat which contains question and a the condidat's answer
            $list = [];
            //loop on the form data
            foreach($form->getData() as $data){
                // check if its instance is question
                if($data instanceof Question){
                    //create new instance of reponseCondidat when the instance is client
                    $reponseCondidat = new ReponseCondidat();
                    //set its question to the data we got
                    $reponseCondidat->setQuestion($data);
                    //add this reponseCondidat to our list
                    array_push($list,$reponseCondidat);

                }
                // else if our data is not a question than its a reponse
                else {
                    $reponse = $this->getDoctrine()->getRepository(Reponse::class)->find($data);
                    // loop our reponsesCondidat list
                    foreach($list as $rep){
                        //if our reponseCondidat's reponse is null than will affect to it this reponse
                        if($rep->getReponse() == null){
                            $rep->setReponse($reponse);
                            //than will add this reponseCondidat to our table which contains the list of reponseCondidat
                            $rep->setListReponsesCondidat($reponseList);
                            $reponseList->addReponse($rep);
                            break;
                        }
                    }

                }
            }
            $em= $this->getDoctrine()->getManager();
            foreach($list as $rep){
                //presist the reonsesCondidat one by one to our database
                $em->persist($rep);
                //calculate the number of correct answers
                if($rep->getReponse()->getId() == $rep->getQuestion()->getRepJust()->getId())
                    $i++;
            }
            //persist the list of reponsesCondidat
            $em->persist($reponseList);
            $em->flush();
            //alert contains the condidat's result
            echo "<script> alert(".$i.") </script>";
            return $this->redirectToRoute('quiz_result', ['id' => $reponseList->getId()]);

        }

        return $this->render('quiz/takeQuiz.html.twig', [ "nom_quiz" => $quiz->getNomQuiz(),
            'form' => $form->createView(),
        ]);
//        return $this->render('quiz/takeQuiz.html.twig', [
//            'quiz' => $quiz,
//        ]);
    }

    /**
     * @Route("/showResult/{id}", name="quiz_result", methods={"GET"})
     */
    public function show(Request $request, ListReponsesCondidat $quiz): Response{

                return $this->render('quiz/showResult.html.twig', [
            'quiz' => $quiz,
        ]);
        return $this->redirectToRoute('quiz_show', ['id' => $quiz->getQuizId()->getId()]);

    }

}
