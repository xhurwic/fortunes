<?php

namespace AppBundle\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Fortune;
use AppBundle\Entity\Comment;
use AppBundle\Form\FortuneType;
use AppBundle\Form\CommentType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $fortunes = new Pagerfanta($this->getDoctrine()->getRepository("AppBundle:Fortune")->findLast());
        $fortunes->setCurrentPage($request->get('page', 1));
        return $this->render('default/index.html.twig', array(
            'fortunes' => $fortunes
        ));
    }

    /**
     * @Route("/vote_up/{id}", name="vote_up")
     */
    public function voteUpAction($id)
    {
        if ($this->get('session')->has($id)) {

            $this->get('session')->getFlashBag()->add('error', 'déjà voté');
            return $this->redirectToRoute("homepage");
        }

        $this->get('session')->set($id, $id );

        $fortune = $this->getDoctrine()->getRepository("AppBundle:Fortune")->find($id);
        $fortune->voteUp();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/vote_down/{id}", name="vote_down")
     */
    public function voteDownAction($id)
    {
        if ($this->get('session')->has($id)) {
            $this->get('session')->getFlashBag()->add('error', 'déjà voté');
            return $this->redirectToRoute("homepage");
        }

        $this->get('session')->set($id, $id);
        $fortune = $this->getDoctrine()->getRepository("AppBundle:Fortune")->find($id);
        $fortune->voteDown();
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("homepage");
    }

    public function showBestRatedAction()
    {
        return $this->render('default/bestRated.html.twig', array (
           'fortuneRateds' => $this->getDoctrine()->getRepository("AppBundle:Fortune")->findRated ()
        ));
    }

    /**
     * @Route("/by_author/{author}", name="by_author")
     */
    public function showByAuthorAction($author)
    {

        return $this->render('default/fortunes_by_author.html.twig', array (
            'fortuneAuthors' => $this->getDoctrine()->getRepository("AppBundle:Fortune")->findByAuthor ($author),
            'author' => $author
        ));
    }

    /**
     * @Route("/new", name="create")
     */
    public function createAction(Request $request)
    {
        $fortune = new Fortune();
        $form = $this->createForm(new FortuneType(), new Fortune());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fortune = $form->getData();
            $em->persist($fortune);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }


        return $this->render('default/new.html.twig', array (
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/single/{title}", name="single")
     */
    public function singleFortune (Request $request, $title)
    {
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), new Comment());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment = $form->getData();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('single', $title);
        }

        return $this->render('default/single.html.twig', array (
            'fortune' =>  $this->getDoctrine()->getRepository("AppBundle:Fortune")->findOne($title),
            'form'    =>  $form->createView()
        ));
    }
}
