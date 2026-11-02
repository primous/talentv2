@extends('emails.layout')

@section('title', 'Demande acceptée par le talent — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Le talent accepte d'échanger sur votre projet !
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $projectRequest->client_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonne nouvelle : le talent <strong style="color: #FFFFFF;">{{ $candidate->professional_name ?: $candidate->full_name }}</strong> a examiné votre brief concernant la prestation <strong style="color: #FFFFFF;">« {{ $projectRequest->title }} »</strong> (Réf : <strong style="color: #FFFFFF;">{{ $projectRequest->reference }}</strong>) et a confirmé son accord pour poursuivre la discussion avec vous.
</p>

<!-- Carte coordonnées vérifiées gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #FFD60A; letter-spacing: 0.08em; margin-bottom: 8px;">
                Coordonnées vérifiées pour joindre le talent
            </div>
            
            <div style="font-size: 14px; line-height: 1.6; color: #FFFFFF;">
                <div style="margin-bottom: 6px;">
                    <strong style="color: #A0A0A0;">Nom du professionnel :</strong> {{ $candidate->professional_name ?: $candidate->full_name }}
                </div>
                @if(!empty($candidate->job_title))
                    <div style="margin-bottom: 6px;">
                        <strong style="color: #A0A0A0;">Spécialité :</strong> {{ $candidate->job_title }}
                    </div>
                @endif

                @php $hasDirectContact = false; @endphp

                @if(!empty($candidate->phone))
                    @php $hasDirectContact = true; @endphp
                    <div style="margin-bottom: 6px;">
                        <strong style="color: #A0A0A0;">Téléphone / WhatsApp :</strong> 
                        <a href="tel:{{ $candidate->phone }}" style="color: #FFD60A; font-weight: bold; text-decoration: underline;">{{ $candidate->phone }}</a>
                    </div>
                @endif

                @if(!empty($candidate->email))
                    @php $hasDirectContact = true; @endphp
                    <div style="margin-bottom: 6px;">
                        <strong style="color: #A0A0A0;">E-mail direct :</strong> 
                        <a href="mailto:{{ $candidate->email }}" style="color: #FFD60A; font-weight: bold; text-decoration: underline;">{{ $candidate->email }}</a>
                    </div>
                @endif

                @if(!$hasDirectContact)
                    <div style="margin-top: 8px; font-size: 13px; color: #A0A0A0; font-style: italic;">
                        Les coordonnées directes sont en cours de mise à jour. Vous pouvez contacter la conciergerie à <a href="mailto:contact@talentclub.ci" style="color: #FFD60A;">contact@talentclub.ci</a>.
                    </div>
                @endif
            </div>
        </td>
    </tr>
</table>

<!-- Section : Début de la prise de contact -->
<div style="font-size: 16px; font-weight: 700; color: #FFFFFF; margin: 0 0 12px 0;">
    Début de la prise de contact
</div>

<p style="font-size: 15px; line-height: 1.7; color: #A0A0A0; margin: 0 0 16px 0;">
    Cette étape constitue le début de la prise de contact et le point de départ de vos échanges. Nous vous invitons à contacter le talent dès à présent pour approfondir les spécifications de votre besoin, convenir des modalités techniques et arrêter ensemble le planning de démarrage.
</p>

<p style="font-size: 13px; line-height: 1.6; color: #8A8A8A; font-style: italic; margin: 0 0 20px 0;">
    ℹ️ Cette acceptation marque le début de la prise de contact et ne constitue pas un contrat définitivement conclu tant que les deux parties n'ont pas validé les termes définitifs.
</p>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
@include('emails.partials.tracking_banner')

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
