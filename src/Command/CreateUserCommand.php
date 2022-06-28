<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: ' ',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher, private UserRepository $userRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'Firstname')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Lastname')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addArgument('role', InputArgument::OPTIONAL, 'Role');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');


        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setIsVerified(true);
        $user->setRoles([$role]);

        //default
        $user->setAddress('05, avenue de symfony');
        $user->setCity('Paris');
        $user->setZipcode('75001');
        $user->setPhone('0785458230');

        $this->userRepository->add($user, true);

        if ($email) {
            $io->note(sprintf('You passed an argument: %s', $email));
        }

        $io->success('User created !!!');

        return Command::SUCCESS;
    }
}
