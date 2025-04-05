<?php

namespace App\Controller;

use App\Dto\DatabaseDto;
use App\Message\GenerateDiagramFromForm;
use App\Repository\DatabaseRepository;
use App\Service\DatabaseTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/form-generate', name: 'form_generate', methods: ['POST'], format: 'json')]
final class GenerateDiagramFromFormController extends AbstractController
{
    public function __construct(
        private readonly DatabaseRepository $databaseRepository,
        private readonly MessageBusInterface $messageBus,
        private readonly DatabaseTransformer $databaseTransformer,

        #[Autowire('%env(MERCURE_TOPIC_FORMAT)%')]
        private readonly string $mercureTopicFormat,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(#[MapRequestPayload] DatabaseDto $dto): JsonResponse
    {
        $database = $this->databaseTransformer->createDatabaseEntityFromDto($dto);
        $this->databaseRepository->save($database);

        /** @var int $databaseId */
        $databaseId = $database->getId();

        $mercureTopic = sprintf($this->mercureTopicFormat, $databaseId);
        $message = new GenerateDiagramFromForm($databaseId, $mercureTopic);
        $this->messageBus->dispatch($message);

        return $this->json($mercureTopic);
    }
}
