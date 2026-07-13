<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.5;
        }
        .header {
            border-bottom: 3px solid #198754;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header .logo {
            font-size: 22pt;
            font-weight: 800;
            color: #198754;
        }
        .header .sub {
            font-size: 8pt;
            color: #666;
        }
        .facture-title {
            text-align: right;
            font-size: 18pt;
            font-weight: 700;
            color: #198754;
            margin-bottom: 5px;
        }
        .facture-subtitle {
            text-align: right;
            font-size: 9pt;
            color: #666;
        }
        .infos {
            width: 100%;
            margin-bottom: 25px;
        }
        .infos td {
            vertical-align: top;
            padding: 5px 10px;
        }
        .infos .box {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
        }
        .infos .box-title {
            font-size: 8pt;
            color: #198754;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .infos .box-content {
            font-size: 9pt;
        }
        table.detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.detail th {
            background: #198754;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 9pt;
            text-transform: uppercase;
        }
        table.detail th.right {
            text-align: right;
        }
        table.detail td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 9pt;
        }
        table.detail td.right {
            text-align: right;
        }
        table.detail tfoot td {
            border-bottom: none;
            padding: 5px 10px;
            font-size: 9pt;
        }
        table.detail tfoot .total {
            font-size: 12pt;
            font-weight: 700;
            color: #198754;
            border-top: 2px solid #198754;
            padding-top: 8px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .footer .iban {
            font-weight: 700;
            color: #333;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: 700;
        }
        .badge-payee { background: #198754; color: #fff; }
        .badge-impayee { background: #dc3545; color: #fff; }
        .badge-partielle { background: #ffc107; color: #333; }
        .paiements {
            margin-top: 20px;
            font-size: 9pt;
        }
        .paiements table {
            width: 100%;
            border-collapse: collapse;
        }
        .paiements th {
            background: #f5f5f5;
            padding: 6px 10px;
            text-align: left;
            font-size: 8pt;
            text-transform: uppercase;
            border-bottom: 2px solid #ddd;
        }
        .paiements td {
            padding: 6px 10px;
            border-bottom: 1px solid #eee;
        }
        .paiements td.right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width:100%">
            <tr>
                <td style="width:50%">
                    <div class="logo">BÉNIN SECURITY</div>
                    <div class="sub">Services de sécurité professionnelle</div>
                    <div class="sub">contact@benin-security.bj | +229 21 30 00 01</div>
                    <div class="sub">01 BP 1234 Cotonou, Bénin</div>
                </td>
                <td style="width:50%">
                    <div class="facture-title">FACTURE</div>
                    <div class="facture-subtitle">N° {{ $facture->numero_facture }}</div>
                    <div class="facture-subtitle">Date d'émission : {{ $facture->date_emission?->format('d/m/Y') }}</div>
                    <div class="facture-subtitle">Échéance : {{ $facture->date_echeance?->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="infos">
        <tr>
            <td style="width:50%">
                <div class="box">
                    <div class="box-title">Facturé à</div>
                    <div class="box-content">
                        <strong>{{ $facture->entreprise?->nom_entreprise ?? 'Client' }}</strong><br>
                        {{ $facture->entreprise?->adresse ?? '' }}<br>
                        @if($facture->entreprise?->ville){{ $facture->entreprise->ville }}, @endif
                        @if($facture->entreprise?->pays){{ $facture->entreprise->pays }}@endif<br>
                        Email : {{ $facture->entreprise?->email ?? '-' }}<br>
                        Tél : {{ $facture->entreprise?->telephone ?? '-' }}
                    </div>
                </div>
            </td>
            <td style="width:50%">
                <div class="box">
                    <div class="box-title">Informations légales</div>
                    <div class="box-content">
                        <strong>Bénin Security Services</strong><br>
                        IFU : 123456789<br>
                        RCCM : RB/BEN/12345<br>
                        Régime : Réel d'impôt<br>
                        Période : {{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) . '/' . $facture->annee }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th style="width:50%">Description</th>
                <th style="width:20%">Période</th>
                <th class="right" style="width:15%">Montant HT</th>
                <th class="right" style="width:15%">Total HT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @if($facture->abonnement)
                    Abonnement {{ $facture->abonnement->formule_label }}
                    @if($facture->abonnement->cycle_facturation)
                    <br><small style="color:#666">Cycle : {{ ucfirst($facture->abonnement->cycle_facturation) }}</small>
                    @endif
                    @else
                    Prestation de sécurité
                    @endif
                </td>
                <td>{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) . '/' . $facture->annee }}</td>
                <td class="right">{{ number_format($facture->montant_ht, 0, ',', ' ') }}</td>
                <td class="right">{{ number_format($facture->montant_ht, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right">Total HT</td>
                <td class="right">{{ number_format($facture->montant_ht, 0, ',', ' ') }} CFA</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right">TVA ({{ $facture->tva }}%)</td>
                <td class="right">{{ number_format($facture->montant_ht * $facture->tva / 100, 0, ',', ' ') }} CFA</td>
            </tr>
            <tr class="total">
                <td colspan="3" style="text-align:right">TOTAL TTC</td>
                <td class="total">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:15px; padding:10px; background:#f8f9fa; border-radius:4px; font-size:9pt;">
        <table style="width:100%">
            <tr>
                <td style="width:50%">
                    <strong>Montant payé :</strong>
                    <span style="color:#198754">{{ number_format($facture->montant_paye, 0, ',', ' ') }} CFA</span>
                </td>
                <td style="width:50%; text-align:right">
                    <strong>Reste à payer :</strong>
                    <span style="color:{{ $facture->montant_restant > 0 ? '#dc3545' : '#198754' }}; font-size:11pt;">
                        {{ number_format($facture->montant_restant, 0, ',', ' ') }} CFA
                    </span>
                </td>
            </tr>
        </table>
    </div>

    @if($facture->paiements && $facture->paiements->count() > 0)
    <div class="paiements">
        <h4 style="font-size:10pt; color:#198754; margin-bottom:8px;">Historique des paiements</h4>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Mode</th>
                    <th>Référence</th>
                    <th class="right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->paiements as $paiement)
                <tr>
                    <td>{{ $paiement->date_paiement?->format('d/m/Y') }}</td>
                    <td>{{ $paiement->mode_paiement }}</td>
                    <td>{{ $paiement->reference ?? '-' }}</td>
                    <td class="right">{{ number_format($paiement->montant, 0, ',', ' ') }} CFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($facture->notes)
    <div style="margin-top:15px; font-size:9pt; color:#666;">
        <strong>Notes :</strong><br>
        {{ $facture->notes }}
    </div>
    @endif

    <div class="footer">
        Bénin Security Services - contact@benin-security.bj - +229 21 30 00 01<br>
        IBAN : BJ123 4567 8901 2345 6789 0123 | Banque : BOA Bénin<br>
        <em>Facture {{ $facture->numero_facture }} - Générée le {{ now()->format('d/m/Y à H:i') }}</em>
    </div>
</body>
</html>
