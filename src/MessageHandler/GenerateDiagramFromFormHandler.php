<?php

namespace App\MessageHandler;

use App\Message\GenerateDiagramFromForm;
use App\Repository\DatabaseRepository;
use App\Service\AICommunicator;
use App\Service\DatabaseFormatter;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GenerateDiagramFromFormHandler
{
    public function __construct(
        private DatabaseRepository $databaseRepository,
        private DatabaseFormatter $databaseFormatter,
        private AICommunicator $aiCommunicator,
        private Filesystem $filesystem,
        private HubInterface $mercurePublisher,

        #[Autowire('%kernel.project_dir%/templates/ai/database-diagram.txt')]
        private string $aiTemplate,

        #[Autowire('%kernel.project_dir%/templates/ai/database-diagram-context.txt')]
        private string $aiContext,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function __invoke(GenerateDiagramFromForm $message): void
    {
        $databaseId = $message->id;
        $database = $this->databaseRepository->findOneBy(['id' => $databaseId]);

        if (null === $database) {
            throw new EntityNotFoundException(sprintf("Database with id '%d' not found.", $databaseId));
        }

        $context = $this->filesystem->readFile($this->aiContext);

        $request = str_replace(
            '___DATA___',
            $this->databaseFormatter->format($database),
            $this->filesystem->readFile($this->aiTemplate)
        );

        $response = $this->aiCommunicator->askAI($context, $request);

        $update = new Update(
            $message->mercureTopic,
            json_encode($response, JSON_THROW_ON_ERROR),
        );
        $this->mercurePublisher->publish($update);
    }
}
