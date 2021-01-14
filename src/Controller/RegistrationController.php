<?php

namespace App\Controller;

use App\Entity\Portrait;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //import du fichier portrait
            $portrait = $form->get('portrait')->getData();
            $fichier = md5(uniqid()) . '.' . $portrait->guessExtension();
            $portrait->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $img = new Portrait();
            $img->setName($fichier);
            $entityManager->persist($img);


            // encode the plain password

            $user->setDate(new DateTime);
            $user->setPortrait($img);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('jpetitgas63@gmail.com', 'JP'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_user")
     */
    public function edit($id, UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = $userRepository->findOneBy(
            [
                'id' => $id
            ]
        );
        if (!$user) {
            throw $this->createNotFoundException("Cet utilisateur n'existe pas");
        }

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //import du fichier portrait
            $portrait = $form->get('portrait')->getData();
            $fichier = md5(uniqid()) . '.' . $portrait->guessExtension();
            $portrait->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $img = new Portrait();
            $img->setName($fichier);
            $entityManager->persist($img);


            // encode the plain password

            $user->setDate(new DateTime);
            $user->setPortrait($img);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();
            return $this->RedirectToRoute('main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('main');
    }
}
