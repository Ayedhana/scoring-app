<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\T24MockService;
use App\Services\T24Service;

class ClientController extends Controller
{
    private $service;

    public function __construct()
    {
        // 👇 CHOIX ICI (tu peux changer plus tard)
        $useMock = true;

        $this->service = $useMock 
            ? new T24MockService()
            : new T24Service();
    }

    // ✅ Liste des clients
    public function index()
    {
        return response()->json($this->service->fetchClients());
    }

    // ✅ Détail d’un client (IMPORTANT)
    public function show($id)
{
    $service = new T24MockService();

    $client = $service->fetchClient($id);

    if (!$client) {
        return response()->json(['message' => 'Client not found'], 404);
    }

    return response()->json($client);
}
}