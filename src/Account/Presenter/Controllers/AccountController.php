<?php

namespace App\Account\Presenter\Controllers;

use App\Account\Application\Command\CreateAccountCommand;
use App\Account\Application\Query\GetAccountBalanceQuery;
use App\Account\Infrastructure\Doctrine\AccountRepository;
use App\Account\Presenter\Form\CreateAccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class AccountController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly AccountRepository $accountRepository
    ){}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/account/create', name: 'account_create')]
    public function create(Request $request): Response
    {
        $command = new CreateAccountCommand();
        $form = $this->createForm(CreateAccountType::class, $command);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch($command);
            $this->addFlash('success', 'Account created successfully.');
            return $this->redirectToRoute('account_create');
        }

        return $this->render('account/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/{id}', name: 'account_show')]
    public function show(Uuid $id): Response
    {
        $query = new GetAccountBalanceQuery($id);
        $enveloppe = $this->bus->dispatch($query);
        $balance = $enveloppe->last(HandledStamp::class)->getResult();

        return $this->render('account/show.html.twig', [
            'accountId' => $id,
            'balance' => $balance,
        ]);
    }
}