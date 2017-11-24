<?php

/*
 * A project of CuongDCDev@gmail.com
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Todo;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TodoController extends Controller {

    /**
     * @Route("/ " , name="ahihi")
     */
    public function listAction(Request $request) {
//        $todos = $this->getDoctrine()
//                ->getRepository("AppBundle:Todo")
//                ->findAll();
//        return $this->render("todo/index.html.twig", array(
//                    "todos" => $todos
        
        $todos = $this->getDoctrine()->getRepository("AppBundle:Todo")->findAll();
        
        return $this->render("todo/index.html.twig" , array(
            "todos" => $todos
        ));
//        return $this->render("todo/index.html.twig");
    }

    /**
     * @Route("/todo/create")
     */
    public function createAction(Request $request) {
        $todo = new Todo;
        $form = $this->createFormBuilder($todo)
                ->add("name", TextType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px"
            )))
                ->add("category", TextType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px;"
                    )
                ))
                ->add("description", TextareaType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px"
                    )
                ))
                ->add("priority", ChoiceType::class, array(
                    "choices" => array(
                            "low" => "Thấp",
                            "high" => "Cao" ),
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px"
                    )
                ))
                ->add("due_date", \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px",
                    )
                ))
                ->add("save", \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array(
                    "label" => "Tạo todo mới! ",
                    "attr" => array(
                        "class" => "form-control"
                    )
                ))
                ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form["name"]->getData();
            $category = $form["category"]->getData();
            $due_date = $form["due_date"]->getData();
            $priority = $form["priority"]->getData();
            
            $now = new \DateTime("now");
            
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDueDate($due_date);
            $todo->setPriority($priority);
            $todo->setCreateDate( $now );
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($todo);    
            $em->flush();
            
            $this->addFlash("notice", "Todo added ");
            
            return $this->redirectToRoute("ahihi");
        }

        return $this->render("todo/create.html.twig", array(
                    "form" => $form->createView()
        ));
    }//create 
    
    
    /**
     * @Route("/todo/details/{id}" , name="todo_single" )
     */
    public function viewAction($id, Request $request){
        $todo = $this->getDoctrine()->getRepository("AppBundle:Todo")->find($id);
        return $this->render("todo/details.html.twig" , array(
            "todo" => $todo 
        ));
    }
    
    /**
     * @Route("/todo/edit/{id}" , name="todo_edit")
     */
    public function editAction( $id, Request $request ){
        $todo = $this->getDoctrine()->getRepository("AppBundle:Todo")->find($id);
        
//        $form = $this->createFormBuilder($todo)->add( "name" , TextType::class, array(
//            "attrs" => array(
//                "class" => "form-control"
//            )
//        ) )
//                ->add("due_date" , \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class , array(
//                    
//                ))
//                ->add("description" , \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class , array(
//                    
//                ))
        $form = $this->createFormBuilder($todo)
                ->add("name", TextType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px"
            )))
                ->add("category", TextType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px;"
                    )
                ))
                ->add("description", TextareaType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px"
                    )
                ))
                ->add("priority", ChoiceType::class, array(
                    "choices" => array(
                            "low" => "Thấp",
                            "high" => "Cao" ),
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px"
                    )
                ))
                ->add("due_date", \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, array(
                    "attr" => array(
                        "class" => "form-control",
                        "style" => "margin-bottom:20px",
                    )
                ))
                ->add("save", \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array(
                    "label" => "Tạo todo mới! ",
                    "attr" => array(
                        "class" => "form-control"
                    )
                ))
                ->getForm();
        
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $name = $form["name"]->getData();
            $category = $form["category"]->getData();
            $description = $form["description"]->getData();
            $priority = $form["priority"]->getData();
            $due_date = $form["due_date"]->getData();
            
            
            $todo->setName( $name );
            $todo->setCategory( $category );
            $todo->setDescription($description);
            $todo->setPriority( $priority );
            $todo->setDueDate($due_date);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            
            $this->addFlash("notice", "SAved successfully! ");
            return $this->redirectToRoute("ahihi");
           
        }
        
        return $this->render( "todo/edit.html.twig" , array(
            "form" => $form->createView()
        ) );
    }
    
    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction( $id , Request $request ){
        $todo = $this->getDoctrine()->getRepository("AppBundle:Todo")->find($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();
        
        $this->addFlash("notice" , "Todo Removed ! ");
        
        return $this->redirectToRoute("ahihi");
    }

}
