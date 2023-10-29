<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(StudentRepository $repo):Response
    {
        $result=$repo->findAll();
        return $this->render('student/test.html.twig',[
            'response' =>$result
        ]);
    }

//methode2 wahda bl manager repository w lokhra b StudentRepository
    #[Route('/fetch2', name: 'fetch2')]
    public function fetch(ManagerRepository $mr):Response
    {
        $repo=$mr->getRepository(Student::Class);
        $result=$repo->findAll();
        return $this->render('student/test.html.twig',[
            'response' =>$result
        ]);
    }
    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $mr, ClassroomRepository $repo):Response{

        $c=$repo->find(1);
       $s= new Student ();
       $s->setName('samar');
       $s->setEmail('samar@gmail.com');
       $s->setAge('23');
       $s->setClassroom($c);

       $em=$mr->getManager();
       $em->persist($s);
       $em->flush();
       return $this->redirectToRoute('fetch');
    }

    #[Route('/addF', name: 'addF')]
    public function addF(ManagerRegistry $mr, ClassroomRepository $repo, Request $req):Response{

    
       $s= new Student (); // 1- insctance
       $form=$this->createForm(StudentType::class,$s);  //2- creation form + recuperationn des donées                 
       $form->handleRequest($req); 
       if($form->isSubmitted()){
        $em=$mr->getManager();  //3-persist+flush
        $em->persist($s);
        $em->flush();
        return $this->redirectToRoute('fetch');
       }
       //2- creation form + recuperationn des donées
       return $this->render('student/add.html.twig',[
        'f'=>$form->createView()
       ]);
    }

    #[Route('/update/id', name: 'update')]
    public function update(ManagerRegistry $mr, StudentRepository $repo, Request $req,$id):Response{

    
       $s= $repo->find; // 1- recuperation objet
       $form=$this->createForm(StudentType::class,$s);  //2- creation form + recuperationn des donées                 
       $form->handleRequest($req); 
       if($form->isSubmitted()){
        $em=$mr->getManager();  //3-persist+flush
        $em->persist($s);
        $em->flush();
        return $this->redirectToRoute('fetch');
       }
       //2- creation form + recuperationn des donées
       return $this->render('student/update.html.twig',[
        'f'=>$form
       ]);
    }




    #[Route('/remove/{id}', name: 'remove')]

    public function remove($id,ManagerRegistry $mr, StudentRepository $repo) :Response
    {
        $student=$repo->find($id);

        $em=$mr->getManager();
        $em->remove($student);
        $em->flush();
        return new Response('removed');
        return $this->redirectToRoute('fetch');
    }
    #[Route('/dql', name: 'dql')]
    public function sqlStudent (EntityManagerInterface $em) : response {
          $req=$em->CreatQuery("select s from App\Entity\Student s.name= :n"); // select * from Student 
if ($request->isMethod('post')){
$value=$request->get('test');
$req->setParameter('n',$value);
$result=$req->getResult();
}
          $result=$req->getResult ();
//dd($result)
return $this->render('student/searchSttudent.html.twig',[
"students"=>$result
]);
    }






}

