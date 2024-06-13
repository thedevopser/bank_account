<?php

namespace App\Account\Presenter\Controllers;

use App\Account\Application\Command\CreateTransactionCommand;
use App\Account\Presenter\Form\CreateTransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class TransactionController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $bus)
    {}

    #[Route('/transaction/create/{accountId}', name: 'transaction_create')]
    public function create(Request $request, Uuid $accountId): Response
    {
        $command = CreateTransactionCommand::fromAccount($accountId);
        $form = $this->createForm(CreateTransactionType::class, $command);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch($command);
            $this->addFlash('success', 'Transaction created successfully.');
            return $this->redirectToRoute('transaction_create', ['accountId' => $accountId]);
        }

        return $this->render('transaction/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}