<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\DivisionRequest;
use App\DTO\DivisionResponse;
use App\DTO\ErrorsResponse;
use App\Service\DivisionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/division", name="division", methods={"POST"})
 */
class DivisionController extends AbstractController
{
    private SerializerInterface $serializer;
    private DivisionService $divisionService;
    private ValidatorInterface $validation;

    public function __construct(
        SerializerInterface $serializer,
        DivisionService $divisionService,
        ValidatorInterface $validation
    ) {
        $this->serializer = $serializer;
        $this->divisionService = $divisionService;
        $this->validation = $validation;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $content = $request->getContent();
        try {
            $dto = $this->serializer->deserialize($content, DivisionRequest::class, 'json');
        } catch (\Throwable $e) {
            return $this->json(new ErrorsResponse(['Json error: ' . $e->getMessage()]), Response::HTTP_BAD_REQUEST);
        }
        $errors = $this->validation->validate($dto);
        if (count($errors)) {
            $resultErrors = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $resultErrors[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(new ErrorsResponse($resultErrors), Response::HTTP_BAD_REQUEST);
        }

        return $this->json(new DivisionResponse($this->divisionService->divisionFloat($dto)));
    }
}
