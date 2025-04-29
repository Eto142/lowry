<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FutureExhibitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exhibitions = [
            [
                'title' => 'SKETCHCODE: Lines of Innovation',
                'theme' => 'Exploring the Role of Sketching in the Digital Age',
                'description' => null,
                'mediums' => 'Pencil sketches, AI-assisted drawing, technical drafts',
                'objective' => 'To spotlight the primal act of sketching and its transformation in contemporary creation.',
                'sections' => json_encode([
                    'Tech Sketch Pioneers',
                    'Hand to Hardware',
                    'Data-Driven Doodles'
                ]),
                'budget' => 28000.00,
                'exhibition_date' => '2025-06-29',
            ],
            [
                'title' => 'ILLUSTRATECH: Culture Reframed',
                'theme' => 'Where Illustration Meets Cultural Identity & Innovation',
                'description' => null,
                'mediums' => 'Digital illustration, vector art, mixed media collage',
                'objective' => 'Merging illustrative traditions with futuristic storytelling.',
                'sections' => json_encode([
                    'Illustrated Futures',
                    'Cultural Layers',
                    'Drawing with Data'
                ]),
                'budget' => 17550.00,
                'exhibition_date' => '2025-07-23',
            ],
            [
                'title' => 'Painted Algorithms',
                'theme' => 'Bridging Fine Art and Machine Intelligence',
                'description' => null,
                'mediums' => 'Oil painting, acrylics, augmented visuals',
                'objective' => 'To explore how human intuition and algorithmic systems co-create visual beauty.',
                'sections' => json_encode([
                    'Oil Intelligence',
                    'Neural Aesthetics',
                    'Painted Patterns'
                ]),
                'budget' => 31250.00,
                'exhibition_date' => '2025-08-28',
            ],
            [
                'title' => 'RENDERED ROOTS',
                'theme' => 'Rediscovering Cultural Narratives through Digital Forms',
                'description' => null,
                'mediums' => 'Digital illustrations, ethnographic painting, hand-drawn maps',
                'objective' => 'To preserve heritage through modern artistic tools.',
                'sections' => json_encode([
                    'Digital Tribes',
                    'Illustrated Ancestry',
                    'Cyberfolklore'
                ]),
                'budget' => 21700.00,
                'exhibition_date' => '2025-09-21',
            ],
            [
                'title' => 'DUALITY IN DESIGN',
                'theme' => 'The Tension and Balance Between Analog and Digital Art',
                'description' => null,
                'mediums' => 'Charcoal drawing, mixed media, digital remix',
                'objective' => 'Reflect on how old techniques influence new media and vice versa.',
                'sections' => json_encode([
                    'Traditional Touch',
                    'Remixed Realities',
                    'Hybrid Perspectives'
                ]),
                'budget' => 18550.00,
                'exhibition_date' => '2025-10-29',
            ],
            [
                'title' => 'CULTURECORE',
                'theme' => 'A Crossroads of Contemporary Art and Societal Shifts',
                'description' => null,
                'mediums' => 'Mixed media, AI-powered portraits, concept sketches',
                'objective' => 'Investigate identity, politics, and tech through personalized visuals.',
                'sections' => json_encode([
                    'Identity Pixels',
                    'Activist Artworks',
                    'Core Expressions'
                ]),
                'budget' => 35200.00,
                'exhibition_date' => '2025-11-30',
            ],
            [
                'title' => 'BRUSH + BYTE',
                'theme' => 'The Digital Brushstroke and Its Cultural Significance',
                'description' => null,
                'mediums' => 'Procreate paintings, watercolors, generative illustrations',
                'objective' => 'To examine the evolving nature of painting in an increasingly digital world.',
                'sections' => json_encode([
                    'Generative Painters',
                    'Abstract Machines',
                    'The New Brushwork'
                ]),
                'budget' => 40850.00,
                'exhibition_date' => '2025-12-28',
            ],
            [
                'title' => 'CODED FIGURES',
                'theme' => 'Artistic Interpretation of Data and Human Emotion',
                'description' => null,
                'mediums' => 'Data-driven illustration, pencil rendering, interactive drawings',
                'objective' => 'Express how art can visually decode emotions in a tech-heavy world.',
                'sections' => json_encode([
                    'Emotional Code',
                    'Interfaced Humanity',
                    'Binary Beauties'
                ]),
                'budget' => 22500.00,
                'exhibition_date' => '2026-01-30',
            ],
            [
                'title' => 'THE IMAGINED MACHINE',
                'theme' => 'Drawing the Future of Artificial Imagination',
                'description' => null,
                'mediums' => 'Ink drawings, machine learning collages, hybrid painting',
                'objective' => 'Envision how machines "see" and reproduce culture through creative media.',
                'sections' => json_encode([
                    'Human-Machine Hybrids',
                    'Robotic Realities',
                    'Drawn by Code'
                ]),
                'budget' => 27400.00,
                'exhibition_date' => '2026-01-29',
            ],
            [
                'title' => 'ARTCODE SYMPHONY',
                'theme' => 'Harmonizing Digital and Traditional Art Practices',
                'description' => null,
                'mediums' => 'Digital painting, audio-reactive sketches, multimedia sculpture',
                'objective' => 'Create a sensory experience through audio-visual storytelling and craftsmanship.',
                'sections' => json_encode([
                    'Sound in Sketch',
                    'Synesthetic Visions',
                    'Motion Paint'
                ]),
                'budget' => 19800.00,
                'exhibition_date' => '2026-03-27',
            ],
            [
                'title' => 'VISUAL CULTURE LOOP',
                'theme' => 'A Look into Artistic Practices Circling Back Through Time',
                'description' => null,
                'mediums' => 'Traditional drawing, layered digital painting, retro-futurist collage',
                'objective' => 'Connect past and present visual culture into a continuous feedback loop.',
                'sections' => json_encode([
                    'Timeline Techniques',
                    'Past Meets Pixel',
                    'Visual Feedback'
                ]),
                'budget' => 32200.00,
                'exhibition_date' => '2026-04-30',
            ]
        ];

        DB::table('future_exhibitions')->insert($exhibitions);
    }
}
