<?php

namespace Application\Contract;

interface ApplicationServiceInterface
{
    public function execute(ApplicationRequestInterface $request);
}