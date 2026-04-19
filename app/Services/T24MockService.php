<?php

namespace App\Services;

class T24MockService
{
    private array $clients = [
    [
        'id' => 'C001',
        'nom' => 'Ali',
        'prenom' => 'Ben Salah',
        'email' => 'ali.bensalah@email.com',
        'balance' => 3000,
        'solde_moyen' => 25000,
        'anciennete_mois' => 72,
        'nombre_incidents' => 0,
        'montant_credits' => 8000,
        'status' => 'active'
    ],
    [
        'id' => 'C002',
        'nom' => 'Ahmed',
        'prenom' => 'Trabelsi',
        'email' => 'ahmed.trabelsi@email.com',
        'balance' => 2000,
        'solde_moyen' => 8000,
        'anciennete_mois' => 24,
        'nombre_incidents' => 3,
        'montant_credits' => 45000,
        'status' => 'active'
    ],
    [
        'id' => 'C003',
        'nom' => 'Sami',
        'prenom' => 'Gharbi',
        'email' => 'sami.gharbi@email.com',
        'balance' => 1500,
        'solde_moyen' => 3000,
        'anciennete_mois' => 8,
        'nombre_incidents' => 6,
        'montant_credits' => 120000,
        'status' => 'inactive'
    ],
    [
        'id' => 'C004',
        'nom' => 'Fatma',
        'prenom' => 'Riahi',
        'email' => 'fatma.riahi@email.com',
        'balance' => 5000,
        'solde_moyen' => 55000,
        'anciennete_mois' => 120,
        'nombre_incidents' => 0,
        'montant_credits' => 5000,
        'status' => 'active'
    ],
    [
        'id' => 'C005',
        'nom' => 'Mohamed',
        'prenom' => 'Khelif',
        'email' => 'med.khelif@email.com',
        'balance' => 3500,
        'solde_moyen' => 18000,
        'anciennete_mois' => 48,
        'nombre_incidents' => 1,
        'montant_credits' => 30000,
        'status' => 'active'
    ],
];

    public function fetchClients(): array
    {
        return $this->clients;
    }

    public function fetchClient(string $id): array
    {
        foreach ($this->clients as $client) {
            if ($client['id'] === $id) {
                return $client;
            }
        }
        return [];
    }
}