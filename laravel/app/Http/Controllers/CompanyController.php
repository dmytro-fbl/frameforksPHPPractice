<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
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

    public function getCompanies(): JsonResponse
    {
        return response()->json(self::DATA, 200);
    }

    public function getCompanyById(string $id): JsonResponse
    {
        foreach (self::DATA as $company) {
            if ($company['id'] === $id) {
                return response()->json($company, 200);
            }
        }

        abort(404, sprintf('Компанію з id "%s" не знайдено', $id));
    }

    public function createCompany(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        if (empty($data)) {
            abort(400, 'Тіло запиту є недійсним або порожнім');
        }

        $requiredFields = [
            'name',
            'industry',
            'employees_count',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                abort(400, sprintf('Поле "%s" є необхідним', $field));
            }
        }

        $data['id'] = (string) rand(4, 100);

        return response()->json($data, 201);
    }

    public function updateCompany(string $id, Request $request): JsonResponse
    {
        $data = $request->json()->all();

        if (empty($data)) {
            abort(400, 'Тіло запиту є недійсним або порожнім');
        }

        $oldCompany = null;

        foreach (self::DATA as $company) {
            if ($company['id'] === $id) {
                $oldCompany = $company;
                break;
            }
        }

        if (!$oldCompany) {
            abort(404, sprintf('Компанію з id "%s" не знайдено', $id));
        }

        $updatableFields = [
            'name',
            'industry',
            'employees_count',
        ];

        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $data)) {
                if ($data[$field] === '' || $data[$field] === null) {
                    abort(400, sprintf('Поле "%s" не може бути порожнім', $field));
                }

                if (!is_string($data[$field]) && !is_numeric($data[$field])) {
                    abort(400, sprintf('Поле "%s" має бути рядком або числом', $field));
                }
            }
        }
        foreach ($updatableFields as $field) {
            if (array_key_exists($field, $data)) {
                $oldCompany[$field] = $data[$field];
            }
        }

        return response()->json($oldCompany, 200);
    }

    public function deleteCompany(string $id): JsonResponse
    {
        foreach (self::DATA as $company) {
            if ($company['id'] === $id) {
                return response()->json(null, 204);
            }
        }

        abort(404, sprintf('Компанію з id "%s" не знайдено', $id));
    }
}
