<?php

/**
 * SoundWave — Ejemplos de consultas Eloquent
 *
 * Ejecutar con:
 *   php artisan tinker
 *   >>> require database_path('queries_example.php');
 */

use App\Models\Album;
use App\Models\Artist;
use App\Models\PlayHistory;
use App\Models\Song;
use App\Models\User;

// ============================================================
// Consulta 1 — Eager Loading para evitar el problema N+1
//
// Sin with(), cada iteración sobre $albums dispararía una query
// adicional para obtener el artista (N+1 queries en total).
// Con with('artist'), Eloquent carga todos los artistas en una
// sola query adicional, sin importar cuántos álbumes haya.
// ============================================================
$albums = Album::with('artist')
    ->where('type', 'album')
    ->orderByDesc('release_date')
    ->limit(10)
    ->get();

echo "=== Consulta 1: Últimos 10 álbumes con su artista (Eager Loading) ===\n";
foreach ($albums as $album) {
    echo "  {$album->title} — {$album->artist->name} ({$album->release_date->format('Y')})\n";
}

// ============================================================
// Consulta 2 — Filtro + ordenamiento sobre artists
//
// Artistas verificados con más de 1M de oyentes mensuales,
// ordenados de mayor a menor popularidad.
// ============================================================
$popularArtists = Artist::where('verified', true)
    ->where('monthly_listeners', '>=', 1_000_000)
    ->orderByDesc('monthly_listeners')
    ->limit(10)
    ->get();

echo "\n=== Consulta 2: Top artistas verificados ===\n";
foreach ($popularArtists as $artist) {
    echo "  {$artist->name} — " . number_format($artist->monthly_listeners) . " oyentes\n";
}

// ============================================================
// Consulta 3 — Eager Loading anidado: canción → álbum → artista
//
// with('album.artist') encadena dos relaciones y las resuelve
// en 3 queries totales en vez de 1 + N + N queries.
// ============================================================
$topSongs = Song::with('album.artist')
    ->orderByDesc('play_count')
    ->limit(10)
    ->get();

echo "\n=== Consulta 3: Top 10 canciones más reproducidas ===\n";
foreach ($topSongs as $rank => $song) {
    echo '#' . ($rank + 1) . " {$song->title} — {$song->album->artist->name}"
        . ' | ' . number_format($song->play_count) . " plays\n";
}

// ============================================================
// Consulta 4 — Relación hasMany con filtro
//
// Álbumes de un artista específico con sus canciones,
// filtrados por tipo y ordenados por fecha de lanzamiento.
// ============================================================
$albums = Album::with('songs')
    ->where('artist_id', 1)
    ->whereIn('type', ['album', 'ep'])
    ->orderByDesc('release_date')
    ->get();

echo "\n=== Consulta 4: Discografía del artista #1 (álbumes y EPs) ===\n";
foreach ($albums as $album) {
    echo "  [{$album->type}] {$album->title} — {$album->songs->count()} canciones\n";
}

// ============================================================
// Consulta 5 — Historial de un usuario con relaciones
//
// Reproducciones completadas del usuario #1, cargando la
// canción y su álbum, ordenadas por fecha descendente.
// ============================================================
$history = PlayHistory::where('user_id', 1)
    ->with(['song.album'])
    ->where('completed', true)
    ->orderByDesc('played_at')
    ->limit(10)
    ->get();

echo "\n=== Consulta 5: Historial completado del usuario #1 ===\n";
foreach ($history as $entry) {
    $song = $entry->song;
    echo "  [{$entry->played_at->format('Y-m-d H:i')}] {$song->title} — álbum: {$song->album->title}\n";
}
