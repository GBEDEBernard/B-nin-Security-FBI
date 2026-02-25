<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat de Prestation de Services</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #198754;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24pt;
            color: #198754;
            margin-bottom: 10px;
        }

        .header h2 {
            font-size: 16pt;
            color: #666;
            font-weight: normal;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-box h3 {
            font-size: 14pt;
            color: #198754;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 200px;
        }

        .info-value {
            flex: 1;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h3 {
            font-size: 14pt;
            color: #198754;
            border-bottom: 1px solid #198754;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .article {
            margin-bottom: 20px;
        }

        .article-title {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 8px;
        }

        .article-content {
            text-align: justify;
            padding-left: 20px;
        }

        .signature-block {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }

        .signature-role {
            font-size: 10pt;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .page-break {
            page-break-after: always;
        }

        .logo-placeholder {
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #999;
        }
    </style>
</head>

<body>
    <!-- En-tête -->
    <div class="header">
        <h1>CONTRAT DE PRESTATION DE SERVICES</h1>
        <h2>DE SÉCURITÉ PRIVÉE</h2>
    </div>

    <!-- Informations sur le contrat -->
    <div class="info-box">
        <h3>RÉFÉRENCE DU CONTRAT</h3>
        <div class="info-row">
            <span class="info-label">N° Proposition :</span>
            <span class="info-value">PROP-{{ str_pad($proposition->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date :</span>
            <span class="info-value">{{ date('d/m/Y') }}</span>
        </div>
    </div>

    <!-- Entreprise -->
    <div class="section">
        <h3>ENTRE LES SOUSSIGNÉS</h3>

        <div class="info-box">
            <h3>LE PRESTATAIRE : BÉNIN SECURITY</h3>
            <div class="info-row">
                <span class="info-label">Adresse :</span>
                <span class="info-value">Cotonou, République du Benin</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value">contact@benin-security.com</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">+229 XX XX XX XX</span>
            </div>
        </div>

        <div class="info-box">
            <h3>LE CLIENT : {{ strtoupper($proposition->nom_entreprise) }}</h3>
            <div class="info-row">
                <span class="info-label">Dénomination sociale :</span>
                <span class="info-value">{{ $proposition->nom_entreprise }}</span>
            </div>
            @if($proposition->nom_commercial)
            <div class="info-row">
                <span class="info-label">Nom commercial :</span>
                <span class="info-value">{{ $proposition->nom_commercial }}</span>
            </div>
            @endif
            @if($proposition->forme_juridique)
            <div class="info-row">
                <span class="info-label">Forme juridique :</span>
                <span class="info-value">{{ $proposition->forme_juridique }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Siège social :</span>
                <span class="info-value">{{ $proposition->adresse ?? '' }}, {{ $proposition->ville ?? '' }}, {{ $proposition->pays ?? '' }}</span>
            </div>
            @if($proposition->numero_registre)
            <div class="info-row">
                <span class="info-label">N° RCCM :</span>
                <span class="info-value">{{ $proposition->numero_registre }}</span>
            </div>
            @endif
            @if($proposition->numeroIdentificationFiscale)
            <div class="info-row">
                <span class="info-label">N° IF :</span>
                <span class="info-value">{{ $proposition->numeroIdentificationFiscale }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Représentants -->
    <div class="section">
        <h3>REPRÉSENTANTS LÉGAUX</h3>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Représentant :</span>
                <span class="info-value">{{ $proposition->representant_nom ?? $proposition->nom_entreprise }}</span>
            </div>
            @if($proposition->representant_fonction)
            <div class="info-row">
                <span class="info-label">Fonction :</span>
                <span class="info-value">{{ $proposition->representant_fonction }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value">{{ $proposition->representant_email ?? $proposition->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">{{ $proposition->representant_telephone ?? $proposition->telephone }}</span>
            </div>
        </div>
    </div>

    <!-- Objet du contrat -->
    <div class="section">
        <h3>ARTICLE 1 - OBJET DU CONTRAT</h3>
        <div class="article">
            <div class="article-content">
                Le présent contrat a pour objet la prestation de services de sécurité {{ $proposition->type_service_label }} au profit de {{ $proposition->nom_entreprise }}.
                <br><br>
                Besoins exprimés : {{ $proposition->nombre_agents }} agent(s) de sécurité.
                @if($proposition->description_besoins)
                <br><br>
                Description complémentaire : {{ $proposition->description_besoins }}
                @endif
            </div>
        </div>
    </div>

    <!-- Durée -->
    <div class="section">
        <h3>ARTICLE 2 - DURÉE DU CONTRAT</h3>
        <div class="article">
            <div class="article-content">
                Le présent contrat est conclu pour une durée de 12 mois à compter de sa date de signature. Il sera tacitement reconduit pour des périodes égales sauf dénonciation par l'une ou l'autre des parties, moyennant un préavis de trois (03) mois adressé par lettre recommandée avec accusé de réception.
            </div>
        </div>
    </div>

    <!-- Obligations du prestataire -->
    <div class="section">
        <h3>ARTICLE 3 - OBLIGATIONS DU PRESTATAIRE</h3>
        <div class="article">
            <div class="article-content">
                Le Prestataire s'engage à :
                <ul style="margin-top: 10px;">
                    <li>Fournir des agents de sécurité qualifiés et formés</li>
                    <li>Assurer la supervision continue des agents</li>
                    <li>Respecter les horaires et dispositifs convenus</li>
                    <li>Établir des rapports périodiques d'activité</li>
                    <li>Assurer le remplacement des agents absents</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Obligations du client -->
    <div class="section">
        <h3>ARTICLE 4 - OBLIGATIONS DU CLIENT</h3>
        <div class="article">
            <div class="article-content">
                Le Client s'engage à :
                <ul style="margin-top: 10px;">
                    <li>Régler les factures dans les délais convenus</li>
                    <li>Fournir les infrastructures nécessaires aux agents</li>
                    <li>Collaborer avec le Prestataire pour la bonne exécution des services</li>
                    <li>Signaler tout incident dans les plus brefs délais</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Conditions financières -->
    <div class="section">
        <h3>ARTICLE 5 - CONDITIONS FINANCIÈRES</h3>
        <div class="article">
            <div class="article-content">
                Les parties conviennent des conditions financières suivantes :
                <br><br>
                <strong>Montant mensuel : </strong>
                @if($proposition->budget_approx)
                {{ number_format($proposition->budget_approx, 0, ',', ' ') }} FCAF HT
                @else
                À définir
                @endif
                <br><br>
                Ce montant sera payable mensuellement d'avance. Tout retard de paiement entraînera des intérêts de retard au taux de 1,5% par mois.
            </div>
        </div>
    </div>

    <!-- Résiliation -->
    <div class="section">
        <h3>ARTICLE 6 - RÉSILIATION</h3>
        <div class="article">
            <div class="article-content">
                En cas de manquement grave par l'une des parties à ses obligations, le présent contrat pourra être résilié de plein droit quinze (15) jours après l'envoi d'une mise en demeure restée sans effet.
            </div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signature-block">
        <div class="signature-box">
            <p><strong>Pour LE PRESTATAIRE</strong></p>
            <div class="signature-line">
                <p class="signature-name">Bénin Security</p>
                <p class="signature-role">Représentant légal</p>
            </div>
        </div>

        <div class="signature-box">
            <p><strong>Pour LE CLIENT</strong></p>
            <div class="signature-line">
                <p class="signature-name">{{ $proposition->representant_nom ?? $proposition->nom_entreprise }}</p>
                <p class="signature-role">{{ $proposition->representant_fonction ?? 'Représentant légal' }}</p>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p><strong>Bénin Security</strong> - Société de sécurité privée</p>
        <p>Siège social : Cotonou, République du Benin | RCCM : RC/2024/001</p>
        <p>Email : contact@benin-security.com | Tel : +229 XX XX XX XX</p>
    </div>
</body>

</html>