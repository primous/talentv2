<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class TalentCompetency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'decision_date' => 'datetime',
            'is_certified' => 'boolean',
            'price' => 'float',
            'score' => 'integer',
            'is_custom' => 'boolean',
            'deliverables' => 'array',
            'workflow_steps' => 'array',
            'faq' => 'array',
        ];
    }

    /**
     * Dictionnaire des 9 catégories officielles et compétences prédéfinies
     */
    public static function getOfficialCategoriesWithSkills(): array
    {
        return [
            'Graphisme et design' => [
                'Création de logo',
                'Identité visuelle et charte graphique',
                'Flyers et affiches publicitaires',
                'Publications pour réseaux sociaux',
                'Bannières et couvertures',
                'Infographies et illustrations',
                'Présentations professionnelles',
                'Brochures, catalogues et dépliants',
                'Design UI/UX',
                'Design de sites web et applications',
                'Retouche et traitement d\'images',
                'Mise en page de documents',
                'Packaging et étiquettes',
                'Supports publicitaires imprimés',
            ],
            'Rédaction et traduction' => [
                'Articles de blog',
                'Textes commerciaux et publicitaires',
                'Copywriting de pages de vente',
                'Fiches produits',
                'Scripts vidéo',
                'Newsletters et e-mails',
                'Correction orthographique et grammaticale',
                'Réécriture de textes',
                'Traduction de documents',
                'Transcription audio et vidéo',
                'CV et lettres de motivation',
                'Rapports et documents professionnels',
                'Livres et e-books',
                'Recherche documentaire et synthèse',
            ],
            'Audiovisuel' => [
                'Montage vidéo',
                'Vidéos courtes pour réseaux sociaux',
                'Montage de vidéos YouTube',
                'Publicités vidéo',
                'Sous-titrage vidéo',
                'Motion design et animation 2D',
                'Animation 3D',
                'Voix off',
                'Enregistrement et montage audio',
                'Nettoyage et amélioration du son',
                'Composition et production musicale',
                'Mixage et mastering',
                'Photographie',
                'Tournage vidéo',
                'Vidéos UGC',
                'Retouche photo',
                'Vidéos avec intelligence artificielle',
            ],
            'Site web et développement' => [
                'Sites vitrines',
                'Boutiques en ligne',
                'Landing pages',
                'Pages de vente et tunnels de conversion',
                'Blogs et portfolios',
                'Refonte de sites web',
                'Développement front-end',
                'Développement back-end',
                'Applications web',
                'Applications mobiles',
                'Développement WordPress',
                'Intégration de maquettes web',
                'Développement et intégration d\'API',
                'Automatisation de tâches et processus',
                'Intégration de formulaires et outils de gestion',
                'Maintenance et correction de bugs',
                'Optimisation des performances web',
                'Sécurisation de sites web',
                'Gestion de bases de données',
                'Configuration de domaines et hébergement',
            ],
            'Marketing digital et communication' => [
                'Audit SEO',
                'SEO on-page',
                'Recherche de mots-clés',
                'Rédaction SEO',
                'Référencement local',
                'Campagnes publicitaires',
                'Publicité Facebook et Instagram',
                'Publicité Google',
                'Publicité TikTok',
                'E-mailing',
                'Automatisation marketing',
                'Stratégie marketing digitale',
                'Étude de marché',
                'Analyse de la concurrence',
                'Création d\'offres commerciales',
                'Génération de prospects',
                'Optimisation de conversion',
                'Lancement de produit',
                'Relations presse',
                'Communication de marque',
                'Analyse des performances marketing',
            ],
            'Réseaux sociaux' => [
                'Création de contenus pour réseaux sociaux',
                'Gestion de pages Facebook',
                'Gestion de comptes Instagram',
                'Gestion de comptes TikTok',
                'Gestion de chaînes YouTube',
                'Community management',
                'Planification éditoriale',
                'Rédaction de publications',
                'Calendriers de contenu',
                'Modération de commentaires',
                'Animation de communauté',
                'Gestion des messages et commentaires',
                'Stratégie de contenu',
                'Optimisation de profils professionnels',
                'Analyse des statistiques',
                'Scripts de vidéos courtes',
            ],
            'Business et conseil' => [
                'Conseil en stratégie d\'entreprise',
                'Business plans',
                'Études de marché',
                'Analyse commerciale',
                'Diagnostic de prospection et de conversion',
                'Organisation administrative',
                'Assistance virtuelle',
                'Gestion d\'agenda et de courriels',
                'Service client à distance',
                'Prospection commerciale',
                'Prise de rendez-vous',
                'Qualification de prospects',
                'Closing et suivi commercial',
                'Présentations professionnelles',
                'Saisie et organisation de données',
                'Tableaux de suivi',
                'Recherche et analyse d\'informations',
                'Procédures et documents internes',
            ],
            'Formation et coaching' => [
                'Formation en informatique',
                'Bureautique',
                'Développement web',
                'Marketing digital',
                'Création de contenu',
                'Design graphique',
                'Vente et négociation',
                'Coaching professionnel',
                'Recherche d\'emploi',
                'Prise de parole',
                'Création d\'entreprise',
                'Tutorat scolaire et universitaire',
                'Cours de langues',
                'Formation aux outils numériques',
                'Organisation et productivité',
            ],
            'Services du quotidien' => [
                'Assistance administrative à distance',
                'Assistance informatique à distance',
                'Aide à la prise en main de logiciels',
                'Organisation de documents numériques',
                'Saisie et mise en forme de documents',
                'Recherche d\'informations en ligne',
                'Préparation de dossiers',
                'Gestion de réservations et rendez-vous',
                'Assistance à l\'organisation d\'activités',
                'Autres prestations pratiques à distance',
            ],
        ];
    }

    /**
     * Retourne la liste des livrables sous forme de tableau propre
     */
    public function getDeliverablesListAttribute(): array
    {
        $raw = $this->deliverables;
        if (is_array($raw)) {
            return array_values(array_filter(array_map('trim', $raw)));
        }
        if (is_string($raw) && trim($raw) !== '') {
            $lines = preg_split('/\r\n|\r|\n/', $raw);
            return array_values(array_filter(array_map(function ($line) {
                return ltrim(trim($line), "-*• \t");
            }, $lines)));
        }
        return [];
    }

    /**
     * Retourne les étapes de déroulement sous forme de tableau normalisé
     */
    public function getWorkflowStepsListAttribute(): array
    {
        $raw = $this->workflow_steps;
        if (is_array($raw)) {
            $normalized = [];
            foreach ($raw as $item) {
                if (is_array($item)) {
                    $normalized[] = [
                        'title' => $item['title'] ?? ($item['titre'] ?? 'Étape'),
                        'desc' => $item['desc'] ?? ($item['description'] ?? ''),
                    ];
                } elseif (is_string($item) && trim($item) !== '') {
                    $parts = explode(':', $item, 2);
                    $normalized[] = [
                        'title' => trim($parts[0]),
                        'desc' => isset($parts[1]) ? trim($parts[1]) : '',
                    ];
                }
            }
            return $normalized;
        }
        if (is_string($raw) && trim($raw) !== '') {
            $lines = preg_split('/\r\n|\r|\n/', $raw);
            $normalized = [];
            foreach ($lines as $line) {
                $trimmed = ltrim(trim($line), "-*• \t0..9.");
                if ($trimmed === '') continue;
                $parts = explode(':', $trimmed, 2);
                $normalized[] = [
                    'title' => trim($parts[0]),
                    'desc' => isset($parts[1]) ? trim($parts[1]) : '',
                ];
            }
            return $normalized;
        }
        return [];
    }

    /**
     * Retourne la FAQ sous forme normalisée [['q' => '...', 'a' => '...']]
     */
    public function getFaqListAttribute(): array
    {
        $raw = $this->faq;
        if (is_array($raw)) {
            $normalized = [];
            foreach ($raw as $item) {
                if (is_array($item) && (!empty($item['q']) || !empty($item['question']))) {
                    $normalized[] = [
                        'q' => $item['q'] ?? ($item['question'] ?? ''),
                        'a' => $item['a'] ?? ($item['reponse'] ?? ($item['answer'] ?? '')),
                    ];
                }
            }
            return $normalized;
        }
        return [];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function decisionLogs(): HasMany
    {
        return $this->hasMany(CompetencyDecisionLog::class)->orderBy('created_at', 'desc');
    }

    public function projectRequests(): HasMany
    {
        return $this->hasMany(ProjectRequest::class, 'talent_competency_id')->orderBy('created_at', 'desc');
    }

    public function isCertified(): bool
    {
        return (bool) ($this->is_certified || ($this->status === 'valide' && (int)$this->score > 0));
    }

    public function getCompetencyNameAttribute(): string
    {
        return $this->name ?? '';
    }

    public function getFormattedPriceAttribute(): ?string
    {
        return $this->price !== null ? number_format((float) $this->price, 0, ',', ' ') . ' FCFA' : null;
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'valide' => 'Compétence vérifiée',
            'active', 'declare' => 'Compétence déclarée (Active)',
            'en_attente' => 'Preuves à vérifier',
            'en_cours' => 'Audit & Test en cours',
            'complements_requis' => 'Compléments demandés',
            'refuse' => 'Non validée',
            default => 'En attente'
        };
    }

    public function hasThumbnail(): bool
    {
        return !empty($this->thumbnail);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (empty($this->thumbnail)) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://') || str_starts_with($this->thumbnail, '/')) {
            return $this->thumbnail;
        }

        return '/storage/' . ltrim($this->thumbnail, '/');
    }

    /**
     * Supprime le fichier physique de la miniature si elle est stockée localement
     * et n'est plus utilisée par aucune autre entité.
     */
    public function deleteThumbnailFile(): void
    {
        if (empty($this->thumbnail)) {
            return;
        }

        $path = $this->thumbnail;
        $relPath = str_starts_with($path, '/storage/') ? substr($path, 9) : $path;
        $relPath = ltrim($relPath, '/');

        // Ne supprimer que les fichiers stockés dans le dossier des miniatures uploadées
        if (str_starts_with($relPath, 'candidates/thumbnails/')) {
            $inOtherCompetency = self::where('id', '!=', $this->id ?? 0)
                ->where(function ($q) use ($path, $relPath) {
                    $q->where('thumbnail', $path)
                      ->orWhere('thumbnail', $relPath)
                      ->orWhere('thumbnail', '/storage/' . $relPath);
                })->exists();

            $inPlatform = PlatformContent::where('file_or_url', $path)
                ->orWhere('file_or_url', $relPath)
                ->orWhere('file_or_url', '/storage/' . $relPath)
                ->exists();

            if (!$inOtherCompetency && !$inPlatform) {
                Storage::disk('public')->delete($relPath);
            }
        }
    }

    /**
     * Dissocie et supprime la miniature de la compétence
     */
    public function removeThumbnail(): void
    {
        $this->deleteThumbnailFile();
        $this->thumbnail = null;
        $this->save();
    }

    /**
     * Met à jour la miniature en supprimant l'ancienne si nécessaire
     */
    public function updateThumbnail(?string $newThumbnail): void
    {
        if ($this->thumbnail && $this->thumbnail !== $newThumbnail) {
            $this->deleteThumbnailFile();
        }
        $this->thumbnail = $newThumbnail;
        $this->save();
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'valide' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
            'active', 'declare' => 'bg-sky-50 text-sky-800 border-sky-300',
            'en_cours' => 'bg-blue-50 text-blue-800 border-blue-200',
            'complements_requis' => 'bg-amber-50 text-amber-800 border-amber-300',
            'refuse' => 'bg-rose-50 text-rose-800 border-rose-200',
            default => 'bg-neutral-100 text-neutral-700 border-neutral-300'
        };
    }

    /**
     * Tous les avis clients associés à cette prestation
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PrestationReview::class, 'talent_competency_id');
    }

    /**
     * Avis clients publics validés et publiés
     */
    public function publishedReviews(): HasMany
    {
        return $this->hasMany(PrestationReview::class, 'talent_competency_id')
            ->where('status', 'publie')
            ->orderByDesc('created_at');
    }

    /**
     * Note moyenne des avis publiés (uniquement s'il y a au moins un avis publié)
     */
    public function getAverageRating(): ?float
    {
        $avg = $this->publishedReviews()->avg('rating');
        return $avg !== null ? round((float) $avg, 1) : null;
    }

    /**
     * Nombre d'avis publiés
     */
    public function getPublishedReviewsCount(): int
    {
        return $this->publishedReviews()->count();
    }
}

