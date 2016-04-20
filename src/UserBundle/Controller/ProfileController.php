<?php

namespace UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\Type\UserProfileType;

class ProfileController extends Controller
{
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $em = $this->getDoctrine()->getManager();
        $er = $em->getRepository('RezervationBundle:Rezervation');
        $rezervations = $er->findByUser($this->getUser());

        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'rezervations' => $rezervations
        ));
    }

    public function editProfileAction(Request $request){
        $user = $this->getUser();

        $userProfileForm = $this->createForm(
            UserProfileType::class,
            $user
        );

        $userProfileForm->handleRequest($request);

        if($userProfileForm->isSubmitted() && $userProfileForm->isValid()) {
                $this->persistData($user);
                $request->getSession()->getFlashBag()->add('succes', 'Data Updated');
                return $this->redirectToRoute('user_profile_edit');
        }

        return $this->render('UserBundle:Profile:edit_profile.html.twig', array(
                'userProfileForm' => $userProfileForm->createView(),
            )
        );
    }

    private function persistData($data){
        try{
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($data);
            $em->flush();
        } catch(Exception $e) {
            return false;
        }
        return true;
    }

}
