<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Seed the testimonials table from the original static set. Idempotent.
     */
    public function run(): void
    {
        if (Testimonial::query()->exists()) {
            $this->command?->info('Testimonials already seeded — skipping.');

            return;
        }

        // [avatar, name, quote]
        $items = [
            ['A', 'Amit Verma', 'The game has attracted many participants due to its unique concept, exciting gameplay, and team spirit. Students are highly motivated and eager to improve their skills, making the future of the sport very promising.'],
            ['B', 'Bhavya Kumari', 'Their energy, commitment, and love for the game demonstrate that Goal Shot Ball is rapidly gaining popularity and recognition.'],
            ['S', 'Saksham Raj', 'Goal Shot Ball is a very exciting and enjoyable game. We really love playing it because it improves our fitness, teamwork, and concentration. The game is easy to learn, full of action, and keeps us motivated to participate every day. We would like more opportunities to play and compete in Goal Shot Ball tournaments.'],
            ['R', 'Roshani Kumari', 'Goal Shot Ball is one of the most interesting sports we have played. It is fun, challenging, and helps us develop confidence and sportsmanship. Every match is exciting, and we look forward to playing it with our friends. We believe this game has a bright future and should be introduced to more students across the country.'],
        ];

        foreach ($items as $i => [$avatar, $name, $quote]) {
            Testimonial::create([
                'name'       => $name,
                'quote'      => $quote,
                'avatar'     => $avatar,
                'rating'     => 5,
                'sort_order' => $i + 1,
                'is_active'  => true,
            ]);
        }

        $this->command?->info('Seeded ' . count($items) . ' testimonials.');
    }
}
