<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
        }
        
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #1a237e;
            padding-bottom: 20px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a237e;
            margin-bottom: 10px;
        }
        
        .company-details {
            font-size: 11px;
            color: #666;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h1 {
            font-size: 28px;
            color: #1a237e;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #666;
        }
        
        /* Info blocks */
        .info-blocks {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .info-block {
            width: 45%;
        }
        
        .info-block h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #1a237e;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .info-block p {
            margin-bottom: 4px;
            font-size: 11px;
        }
        
        .info-block .label {
            font-weight: bold;
            color: #666;
            width: 80px;
            display: inline-block;
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #1a237e;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        /* Totals */
        .totals {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .totals-row.total {
            background-color: #1a237e;
            color: white;
            font-weight: bold;
            font-size: 14px;
            padding: 12px;
            margin-top: 5px;
        }
        
        .totals-row.paid {
            background-color: #2e7d32;
            color: white;
            font-weight: bold;
        }
        
        .totals-row.due {
            background-color: #c62828;
            color: white;
            font-weight: bold;
        }
        
        /* Payment info */
        .payment-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .payment-info h3 {
            font-size: 12px;
            color: #1a237e;
            margin-bottom: 10px;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        
        /* Status badges */
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background-color: #2e7d32;
            color: white;
        }
        
        .status-pending {
            background-color: #f57c00;
            color: white;
        }
        
        .status-overdue {
            background-color: #c62828;
            color: white;
        }
        
        /* Print styles */
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
            }
        }
        
        /* No print */
        .no-print {
            display: none;
        }
        
        /* For screen */
        @media screen {
            .invoice-container {
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">{{ $entreprise->nom_entreprise ?? 'Entreprise' }}</div>
                <div class="company-details">
                    @if($entreprise->adresse)
                        <p>{{ $entreprise->adresse }}</p>
                    @endif
                    @if($entreprise->ville || $entreprise->pays)
                        <p>{{ $entreprise->ville ?? '' }}@if($entreprise->ville && $entreprise->pays), @endif{{ $entreprise->pays ?? '' }}</p>
                    @endif
                    @if($entreprise->telephone)
                        <p>Tél: {{ $entreprise->telephone }}</p>
                    @endif
                    @if($entreprise->email)
                        <p>Email: {{ $entreprise->email }}</p>
                    @endif
                    @if($entreprise->numero_registre)
                        <p>RCCM: {{ $entreprise->numero_registre }}</p>
                    @endif
                    @if($entreprise->numeroIdentificationFiscale)
                        <p>NIF: {{ $entreprise->numeroIdentificationFiscale }}</p>
                    @endif
                </div>
            </div>
            <div class="invoice-title">
                <h1>FACTURE</h1>
                <div class="invoice-number">
                    <strong>N°:</strong> {{ $facture->numero_facture }}<br>
                    <strong>Date:</strong> {{ $facture->date_emission ? $facture->date_emission->format('d/m/Y') : '-' }}
                </div>
            </div>
        </div>

        <!-- Info blocks -->
        <div class="info-blocks">
            <div class="info-block">
                <h3>Client</h3>
                @if($client)
                    <p><strong>{{ $client->nomAffichage }}</strong></p>
                    @if($client->type_client == 'entreprise' && $client->raison_sociale)
                        <p>{{ $client->raison_sociale }}</p>
                    @endif
                    @if($client->adresse)
                        <p>{{ $client->adresse }}</p>
                    @endif
                    @if($client->ville || $client->pays)
                        <p>{{ $client->ville ?? '' }}@if($client->ville && $client->pays), @endif{{ $client->pays ?? '' }}</p>
                    @endif
                    @if($client->telephone)
                        <p>Tél: {{ $client->telephone }}</p>
                    @endif
                    @if($client->email)
                        <p>Email: {{ $client->email }}</p>
                    @endif
                @else
                    <p>-</p>
                @endif
            </div>
            <div class="info-block">
                <h3>Détails de la Facture</h3>
                <p><span class="label">Référence:</span> {{ $facture->reference ?? '-' }}</p>
                <p><span class="label">Période:</span> {{ $facture->mois ? sprintf('%02d/%d', $facture->mois, $facture->annee) : '-' }}</p>
                <p><span class="label">Échéance:</span> {{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : '-' }}</p>
                <p><span class="label">Statut:</span> 
                    @if($facture->statut == 'payee')
                        <span class="status status-paid">Payée</span>
                    @elseif($facture->statut == 'en_attente' || $facture->statut == 'emise')
                        <span class="status status-pending">En attente</span>
                    @else
                        <span class="status status-overdue">{{ $facture->statut }}</span>
                    @endif
                </p>
                @if($contrat)
                    <p><span class="label">Contrat:</span> {{ $contrat->numero_contrat ?? $contrat->id }}</p>
                @endif
            </div>
        </div>

        <!-- Items table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center">Qté</th>
                    <th class="text-right">Prix Unit.</th>
                    <th class="text-right">Montant HT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $details = $facture->detail_prestation;
                    if (is_string($details)) {
                        $details = json_decode($details, true);
                    }
                @endphp
                
                @if($details && is_array($details))
                    @foreach($details as $type => $detail)
                        <tr>
                            <td>
                                <strong>{{ ucfirst(str_replace('_', ' ', $type)) }}</strong>
                                @if(isset($detail['agents']))
                                    <br><small>{{ $detail['agents'] }} agent(s)</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $detail['heures'] ?? '-' }}</td>
                            <td class="text-right">
                                @if(isset($detail['agents']) && isset($detail['heures']) && $detail['heures'] > 0)
                                    {{ number_format(($facture->montant_ht ?? 0) / array_sum(array_column($details, 'heures')) * $detail['heures'] / max($detail['agents'], 1), 0, ',', ' ') }} CFA
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">
                                @if(isset($detail['agents']) && isset($detail['heures']))
                                    {{ number_format(($facture->montant_ht ?? 0) / array_sum(array_column($details, 'heures')) * $detail['heures'], 0, ',', ' ') }} CFA
                                @else
                                    {{ number_format($facture->montant_ht ?? 0, 0, ',', ' ') }} CFA
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>Prestation de sécurité - {{ $facture->reference ?? 'Facture' }}</td>
                        <td class="text-center">1</td>
                        <td class="text-right">{{ number_format($facture->montant_ht ?? 0, 0, ',', ' ') }} CFA</td>
                        <td class="text-right">{{ number_format($facture->montant_ht ?? 0, 0, ',', ' ') }} CFA</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span>Montant HT</span>
                <span>{{ number_format($facture->montant_ht ?? 0, 0, ',', ' ') }} CFA</span>
            </div>
            <div class="totals-row">
                <span>TVA ({{ $facture->tva ?? 18 }}%)</span>
                <span>{{ number_format(($facture->montant_ttc ?? 0) - ($facture->montant_ht ?? 0), 0, ',', ' ') }} CFA</span>
            </div>
            <div class="totals-row total">
                <span>Montant TTC</span>
                <span>{{ number_format($facture->montant_ttc ?? 0, 0, ',', ' ') }} CFA</span>
            </div>
            @if($facture->montant_paye > 0)
                <div class="totals-row paid">
                    <span>Montant Payé</span>
                    <span>{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</span>
                </div>
            @endif
            @if($facture->montant_restant > 0)
                <div class="totals-row due">
                    <span>Montant Restant</span>
                    <span>{{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA</span>
                </div>
            @endif
        </div>

        <!-- Payment history -->
        @if($paiements && $paiements->count() > 0)
            <div class="payment-info">
                <h3>Historique des Paiements</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Référence</th>
                            <th class="text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiements as $paiement)
                            <tr>
                                <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $paiement->mode_paiement ?? '-' }}</td>
                                <td>{{ $paiement->reference ?? '-' }}</td>
                                <td class="text-right">{{ number_format($paiement->montant, 0, ',', ' ') }} CFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Notes -->
        @if($facture->notes)
            <div class="payment-info">
                <h3>Notes</h3>
                <p>{{ $facture->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>{{ $entreprise->nom_entreprise ?? 'Bénin Security' }} - {{ $entreprise->email ?? 'contact@benin-security.bj' }}</p>
        </div>

        <!-- Print buttons -->
        @if(!isset($print) || !$print)
            <div class="no-print" style="margin-top: 20px; text-align: center;">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
                <a href="{{ route('admin.superadmin.facturation.pdf', $facture->id) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Télécharger PDF
                </a>
            </div>
        @endif
    </div>
</body>
</html>

