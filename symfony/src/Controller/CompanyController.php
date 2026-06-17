<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/companies')]
class CompanyController extends AbstractController
{
    private const DATA = [
        [
            'id' => '1',
            'name' => 'Global Tech',
            'industry' => 'IT',
            'employees_count' => '500',
        ],
        [
            'id' => '2',
            'name' => 'ТОВ БудПром',
            'industry' => 'Будівництво',
            'employees_count' => '120',
        ],
        [
            'id' => '3',
            'name' => 'АгроСвіт',
            'industry' => 'Сільське господарство',
            'employees_count' => '350',
        ]
    ];

    #[Route('', methods: ['GET'])]
    public function getCompanies(): JsonResponse
    {
        return new JsonResponse(self::DATA, 200);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getCompanyById(string $id): JsonResponse
    {
        foreach (self::DATA as $company) {
            if ($company['id'] === $id) {
                return new JsonResponse($company, 200);
            }
        }

        return new JsonResponse(['error' => sprintf('Компанію з id "%s" не знайдено', $id)], 404);
    }

    #[Route('', methods: ['POST'])]
    public function createCompany(Request $request): JsonResponse
    {
        // У Symfony дані з JSON дістаються так:
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return new JsonResponse(['error' => 'Тіло запиту є недійсним або порожнім'], 400);
        }

        $requiredFields = [
            'name',
            'industry',
            'employees_count',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return new JsonResponse(['error' => sprintf('Поле "%s" є необхідним', $field)], 400);
            }
        }

        $data['id'] = (string) rand(4, 100);

        return new JsonResponse($data, 201);
    }

    #[Route('/{id}', methods: ['PATCH', 'PUT'])]
    public function updateCompany(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) {
            return new JsonResponse(['error' => 'Тіло запиту є недійсним або порожнім'], 400);
        }

        $oldCompany = null;

        foreach (self::DATA as $company) {
            if ($company['id'] === $id) {
                $oldCompany = $company;
                break;
            }
        }

        if (!$oldCompany) {
            return new JsonResponse(['error' => sprintf('Компанію з id "%s" не знайдено', $id)], 404);
        }

        $updatableFields = [
            'name',
            'industry',
            'employees_count',
        ];

        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $data)) {
                if ($data[$field] === '' || $data[$field] === null) {
                    return new JsonResponse(['error' => sprintf('Поле "%s" не може бути порожнім', $field)], 400);
                }

                if (!is_string($data[$field]) && !is_numeric($data[$field])) {
                    return new JsonResponse(['error' => sprintf('Поле "%s" має бути рядком або числом', $field)], 400);
                }
            }
        }

        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $data)) {
                $oldCompany[$field] = $data[$field];
            }
        }

        return new JsonResponse($oldCompany, 200);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteCompany(string $id): JsonResponse
    {
        foreach (self::DATA as $company) {
            if ($company['id'] === $id) {
                return new JsonResponse(null, 204);
            }
        }

        return new JsonResponse(['error' => sprintf('Компанію з id "%s" не знайдено', $id)], 404);
    }
}
