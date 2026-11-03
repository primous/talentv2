<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\TalentCompetency;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    /**
     * Génère un fichier sitemap.xml conforme aux standards Google Search Console et Sitemaps.org
     */
    public function sitemap(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://talentv2.vercel.app'), '/');
        if (str_starts_with($baseUrl, 'http://') && !str_contains($baseUrl, 'localhost')) {
            $baseUrl = 'https://' . substr($baseUrl, 7);
        }

        $now = now()->toAtomString();

        // 1. Pages statiques prioritaires
        $staticUrls = [
            [
                'loc' => $baseUrl . '/',
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => $baseUrl . '/talents',
                'lastmod' => $now,
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => $baseUrl . '/candidature',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/projet/site-vitrine',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/projet/logo-identite',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/projet/video-courte',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/projet/application-mobile',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/projet/copywriting-social',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $baseUrl . '/projet/uiux-design-system',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
        ];

        // 2. Prestations dynamiques des talents publiés et certifiés
        $dynamicUrls = [];
        try {
            $competencies = TalentCompetency::with('candidate')
                ->whereIn('status', ['valide', 'active', 'declare'])
                ->whereHas('candidate', function ($query) {
                    $query->where('status', 'valide')
                          ->where('visibility_status', 'publie');
                })
                ->get();

            foreach ($competencies as $comp) {
                $slug = Str::slug($comp->competency_name ?: $comp->name);
                $path = $slug ? "{$comp->id}-{$slug}" : "{$comp->id}";
                $lastmod = ($comp->updated_at ?: $comp->created_at ?: now())->toAtomString();

                $dynamicUrls[] = [
                    'loc' => $baseUrl . '/prestation/' . $path,
                    'lastmod' => $lastmod,
                    'changefreq' => 'weekly',
                    'priority' => '0.8'
                ];
            }
        } catch (\Throwable $e) {
            // En cas d'indisponibilité momentanée de la BD, on renvoie les URLs statiques
        }

        $allUrls = array_merge($staticUrls, $dynamicUrls);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($allUrls as $entry) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($entry['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <lastmod>" . $entry['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $entry['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $entry['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        // Écriture du fichier physique dans public/ pour accès statique haute performance si possible
        try {
            $publicSitemapPath = public_path('sitemap.xml');
            if (is_writable(dirname($publicSitemapPath))) {
                @file_put_contents($publicSitemapPath, $xml);
            }
        } catch (\Throwable $e) {
            // Ignore si système de fichier en lecture seule
        }

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Génère un fichier robots.txt conforme aux exigences Googlebot
     */
    public function robots(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://talentv2.vercel.app'), '/');
        if (str_starts_with($baseUrl, 'http://') && !str_contains($baseUrl, 'localhost')) {
            $baseUrl = 'https://' . substr($baseUrl, 7);
        }

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /candidat/dashboard\n";
        $content .= "Disallow: /candidat/activer/\n";
        $content .= "Disallow: /mes-demandes\n";
        $content .= "Disallow: /mes-demandes/\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /up\n\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
