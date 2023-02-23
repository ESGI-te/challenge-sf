<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Bundle\SecurityBundle\Security;

class PaymentService
{

    private User $user;

    public function __construct(Security $security,UserRepository $userRepository)
    {
        $user_instance = $security->getUser()->getUserIdentifier();
        $UserObject = $userRepository->findOneBy(['email' => $user_instance]);
        $this->user = $UserObject;
    }

    /**
     * @throws ApiErrorException
     */
    public function paymentCheckout(StripeClient $stripeClient, $successURL, $cancelURL): \Stripe\Checkout\Session
    {
        return $stripeClient->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Toke premium',
                    ],
                    'unit_amount' => 1000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successURL,
            'cancel_url' => $cancelURL
        ]);
    }

    public function encryptToken($slug): string
    {
        return hash('sha256', $slug);
    }

    /**
     * @return User
     */
    public function getInstance(): User
    {
        return $this->user;
    }

    public function updateRoles($roles): void
    {
        $this->user->setRoles($roles);
    }

}