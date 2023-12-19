<?php

namespace App\Service;

class PaginatorService
{
    public function paginate(array $data, int $page, int $perPage): array
    {
        $totalItems = count($data);
        $offset = ($page - 1) * $perPage;
        $paginatedData = array_slice($data, $offset, $perPage);

        return [
        'data' => $paginatedData,
        'totalItems' => $totalItems,
        'currentPage' => $page,
        'itemsPerPage' => $perPage,
        'totalPages' => ceil($totalItems / $perPage),
        ];
    }
}
