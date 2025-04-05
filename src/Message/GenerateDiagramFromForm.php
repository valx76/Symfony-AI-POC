<?php

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
final readonly class GenerateDiagramFromForm
{
    public function __construct(
        public int $id,
        public string $mercureTopic,
    ) {
    }
}
