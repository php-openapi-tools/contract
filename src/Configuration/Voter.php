<?php

namespace ApiClients\Tools\OpenApiClientGenerator\Configuration;

use ApiClients\Tools\OpenApiClientGenerator\Contract\Voter as VoterContract;
use EventSauce\ObjectHydrator\MapFrom;

final readonly class Voter
{
    /**
     * @param array<class-string<VoterContract\ListOperation>>|null $listOperation
     * @param array<class-string<VoterContract\StreamOperation>>|null $streamOperation
     */
    public function __construct(
        #[MapFrom('listOperation')]
        public ?array $listOperation,
        #[MapFrom('streamOperation')]
        public ?array $streamOperation,
    ) {
    }
}