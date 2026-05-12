<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private const N = 1000;

    private array $firstNames = [
        'James', 'John', 'Robert', 'Michael', 'William', 'David', 'Richard', 'Joseph',
        'Mary', 'Patricia', 'Jennifer', 'Linda', 'Barbara', 'Elizabeth', 'Susan', 'Jessica',
        'Carlos', 'Ana', 'Luis', 'Maria', 'Diego', 'Sofia', 'Andres', 'Valentina',
        'Liam', 'Emma', 'Noah', 'Olivia', 'Ethan', 'Ava', 'Mason', 'Isabella',
    ];

    private array $lastNames = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
        'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
        'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson',
    ];

    private array $countries = ['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'ES', 'MX', 'BR', 'JP', 'KR', 'AR', 'CO', 'CL'];

    private array $adjectives = [
        'Dark', 'Neon', 'Velvet', 'Electric', 'Broken', 'Golden', 'Silver', 'Lost',
        'Wild', 'Midnight', 'Cosmic', 'Digital', 'Echo', 'Fading', 'Glowing', 'Hidden',
        'Inner', 'Liquid', 'Mystic', 'Northern', 'Ocean', 'Prism', 'Quiet', 'Rising',
        'Sacred', 'Timeless', 'Urban', 'Vivid', 'Wandering', 'Zenith',
    ];

    private array $nouns = [
        'Dream', 'Fire', 'Storm', 'Light', 'Shadow', 'Wave', 'Echo', 'Heart',
        'Soul', 'Moon', 'Star', 'Road', 'Sky', 'River', 'Mountain', 'Desert',
        'City', 'Night', 'Day', 'Time', 'Mind', 'Voice', 'Dance', 'Song',
        'World', 'Space', 'Rain', 'Wind', 'Sun', 'Ocean',
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->command->info('Seeding users...');
        $this->seedUsers();

        $this->command->info('Seeding artists...');
        $this->seedArtists();

        $this->command->info('Seeding albums...');
        $this->seedAlbums();

        $this->command->info('Seeding songs...');
        $this->seedSongs();

        $this->command->info('Seeding play_histories...');
        $this->seedPlayHistories();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('Done! 5,000 records seeded across 5 tables.');
    }

    private function randomName(): string
    {
        return $this->firstNames[array_rand($this->firstNames)]
            . ' ' . $this->lastNames[array_rand($this->lastNames)];
    }

    private function randomTitle(): string
    {
        return $this->adjectives[array_rand($this->adjectives)]
            . ' ' . $this->nouns[array_rand($this->nouns)];
    }

    private function seedUsers(): void
    {
        $hashedPassword = Hash::make('password');
        $plans          = ['free', 'free', 'free', 'premium'];
        $now            = now();

        $data = [];
        for ($i = 1; $i <= self::N; $i++) {
            $data[] = [
                'name'       => $this->randomName(),
                'username'   => 'user_' . $i,
                'email'      => 'user' . $i . '@soundwave.test',
                'password'   => $hashedPassword,
                'plan'       => $plans[array_rand($plans)],
                'birth_date' => date('Y-m-d', mktime(0, 0, 0, rand(1, 12), rand(1, 28), rand(1965, 2005))),
                'country'    => $this->countries[array_rand($this->countries)],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }

    private function seedArtists(): void
    {
        $now  = now();
        $data = [];
        for ($i = 1; $i <= self::N; $i++) {
            $name   = $this->randomName() . ' ' . $i;
            $data[] = [
                'name'              => $name,
                'slug'              => Str::slug($name),
                'bio'               => 'Artista reconocido por su estilo único y su impacto en la escena musical.',
                'image_url'         => 'https://picsum.photos/seed/artist' . $i . '/400/400',
                'country'           => $this->countries[array_rand($this->countries)],
                'monthly_listeners' => rand(1000, 50000000),
                'verified'          => (bool) rand(0, 1),
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('artists')->insert($chunk);
        }
    }

    private function seedAlbums(): void
    {
        $now   = now();
        $types = ['album', 'album', 'album', 'single', 'ep'];
        $data  = [];
        for ($i = 1; $i <= self::N; $i++) {
            $data[] = [
                'artist_id'    => rand(1, self::N),
                'title'        => $this->randomTitle(),
                'type'         => $types[array_rand($types)],
                'release_date' => date('Y-m-d', mktime(0, 0, 0, rand(1, 12), rand(1, 28), rand(2000, 2024))),
                'cover_url'    => 'https://picsum.photos/seed/album' . $i . '/500/500',
                'total_tracks' => rand(4, 16),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('albums')->insert($chunk);
        }
    }

    private function seedSongs(): void
    {
        $now  = now();
        $data = [];
        for ($i = 1; $i <= self::N; $i++) {
            $data[] = [
                'album_id'         => rand(1, self::N),
                'title'            => $this->randomTitle(),
                'duration_seconds' => rand(120, 360),
                'track_number'     => rand(1, 16),
                'file_url'         => 'https://cdn.soundwave.test/songs/song_' . $i . '.mp3',
                'play_count'       => rand(0, 10000000),
                'explicit'         => rand(0, 4) === 0,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('songs')->insert($chunk);
        }
    }

    private function seedPlayHistories(): void
    {
        $now  = now();
        $data = [];
        for ($i = 1; $i <= self::N; $i++) {
            $duration = rand(120, 360);
            $played   = rand(30, $duration);
            $data[]   = [
                'user_id'        => rand(1, self::N),
                'song_id'        => rand(1, self::N),
                'played_at'      => now()->subMinutes(rand(1, 43200))->format('Y-m-d H:i:s'),
                'seconds_played' => $played,
                'completed'      => $played >= $duration * 0.9,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('play_histories')->insert($chunk);
        }
    }
}
