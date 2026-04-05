<?php
/*
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Services\T24Service;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected T24Service $t24Service;

    public function __construct(T24Service $t24Service)
    {
        $this->t24Service = $t24Service;
    }

    public function index(Request $request)
    {
        $clients = Client::query()->paginate(15);

        return ClientResource::collection($clients);
    }

    public function getAllClients(Request $request)
    {
        $clients = Client::with(['comptes', 'credits'])
            ->paginate(20);

        return response()->json($clients);
    }

    public function getClientDetails($id)
    {
        $client = Client::with(['comptes.transactions', 'credits'])->find($id);

        if (! $client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    public function show(Client $client)
    {
        return new ClientResource($client->load('scores', 'historiqueScores'));
    }

    public function syncFromT24(Client $client)
    {
        $remote = $this->t24Service->fetchClient($client->numero_client);

        $client->update([
            'nom' => $remote['last_name'] ?? $client->nom,
            'prenom' => $remote['first_name'] ?? $client->prenom,
            'cin' => $remote['id_number'] ?? $client->cin,
            'date_naissance' => $remote['birth_date'] ?? $client->date_naissance,
            'telephone' => $remote['phone'] ?? $client->telephone,
            'email' => $remote['email'] ?? $client->email,
            'adresse' => $remote['address'] ?? $client->adresse,
            'type_client' => $remote['client_type'] ?? $client->type_client,
        ]);

        return new ClientResource($client);
    }
}
*/
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ClientController extends Controller
{
    public function getClients()
    {
        $response = Http::get('http://localhost:3000/api/clients');

        return $response->json();
    }
}