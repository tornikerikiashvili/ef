<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Beginners guide to start your photography journey', 'Kimono Photography is a full-service photography company providing wedding, newborn, fashion & portfolio photography. Our portfolio of completed work includes highly acclaimed and award-winning projects for clients around the country & globally.'],
            ['Twenty photography tips to make photos amazing', 'Discover practical tips from our team to improve your photography skills and get the most out of every shoot.'],
            ['What Norway is best spots for photography', 'We explore the most photogenic locations in Norway and how to capture them at the right time.'],
            ['How I take my cool shots for my wildlife reels', 'Behind the scenes of our wildlife photography sessions and the gear we use in the field.'],
            ['How you should prepare your studio before a shoot', 'A checklist for studio preparation that will help you run smooth and professional sessions.'],
            ['Best cameras 2023 for travel photography', 'Our roundup of the best cameras for travel photography this year, from compact to full-frame.'],
            ['Beginners guide to start your photography journey', 'A second take on starting out in photography with updated advice and resources.'],
            ['Beginners guide to start newborn photography', 'Essential tips and safety guidelines for photographing newborns and young babies.'],
            ['Best wild photographs taken by our photographers', 'A selection of award-winning wildlife images from our team around the world.'],
            ['California mansion residence project showcase', 'Full case study of our interior and exterior photography for a luxury California residence.'],
            ['Well decor house in Sydney', 'We document a beautifully decorated house in Sydney with natural light and detail shots.'],
            ['Huge large area bedroom design', 'How we captured a large master bedroom to showcase space and design.'],
            ['Recent trends in designing space', 'An overview of current interior design trends and how to photograph them.'],
            ['Using natural light in indoor photography', 'Techniques for making the most of natural light in indoor and real estate shoots.'],
            ['Five poses every portrait photographer should know', 'Classic poses that flatter every subject and work in any location.'],
            ['Editing workflow for wedding photography', 'How we edit thousands of wedding photos efficiently without losing quality.'],
            ['Why backup and storage matter for photographers', 'Our approach to backing up and archiving client work safely.'],
            ['Building a photography brand that attracts clients', 'Marketing and branding advice for photographers who want to grow their business.'],
            ['Behind the lens: a day with our fashion team', 'A typical day on set with our fashion photography team.'],
            ['Seasonal photography ideas for the coming year', 'Ideas and planning tips for spring, summer, autumn and winter shoots.'],
        ];

        foreach ($items as $i => [$title, $teaser]) {
            News::create([
                'title' => ['en' => $title],
                'teaser' => ['en' => $teaser],
                'text_content' => ['en' => '<p>' . $teaser . '</p><p>Kimono Photography is a full-service photography company providing wedding, newborn, fashion & portfolio photography. Our portfolio of completed work includes highly acclaimed and award-winning projects for clients around the country & globally also.</p>'],
                'news_category_id' => null,
                'cover_photo' => null,
            ]);
        }
    }
}
