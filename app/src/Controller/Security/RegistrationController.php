<?php

namespace App\Controller\Security;

use App\Entity\Customer;
use App\Form\Frontend\RegistrationFormType;
use App\Service\EntityManagerServices\CustomerManagerInterface;
use App\Service\Frontend\DataManagerInterface;
use App\Service\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;

class RegistrationController extends AbstractController
{

    public function __construct(
        private readonly CustomerManagerInterface $customerManagerService,
        private readonly MailerServiceInterface $mailerService,
        private readonly FormLoginAuthenticator $authenticator,
    )
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserAuthenticatorInterface $authenticatorManager
    ): Response
    {
        $customer = new Customer();
        $form = $this->createForm(RegistrationFormType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Customer $customerData */
            $customerData = $form->getData();

            $plainPassword = $form->get('plainPassword')->getData();

            if($plainPassword !== null) {
                $customerData->setPassword($this->customerManagerService->getHashPassword($customerData, $plainPassword));
            }

            $this->customerManagerService->updateUser($customerData);
            $this->mailerService->sendEmail($customerData->getEmail(), sprintf('Welcome %s', $customerData->getLastName()),
                'Your account is created.');

            $authenticatorManager->authenticateUser($customerData, $this->authenticator, $request, [new RememberMeBadge()]);

            if($request->get('_target_path') !== null) {
                return new RedirectResponse($request->get('_target_path'));
            }

            return $this->redirectToRoute('app_book_room');
        }

        return $this->render('pages/register/index.html.twig', [
            'registrationForm' => $form->createView(),
            'back_to_your_page' => $request->headers->get('referer')
        ]);
    }
}