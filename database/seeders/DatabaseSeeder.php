<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário administrador
        $admin = User::factory()->create([
            'name' => 'Elian Silva',
            'email' => 'elian@example.com',
            'password' => bcrypt('password'),
        ]);

        // Criar usuários de exemplo
        $users = User::factory(10)
            ->sequence(
                [
                    'name' => 'Maria Silva',
                    'email' => 'maria@exemplo.com',
                ],
                [
                    'name' => 'João Santos',
                    'email' => 'joao@exemplo.com',
                ],
                [
                    'name' => 'Ana Oliveira',
                    'email' => 'ana@exemplo.com',
                ],
                [
                    'name' => 'Pedro Costa',
                    'email' => 'pedro@exemplo.com',
                ],
            )
            ->create();

        // Criar posts para cada usuário
        $users->each(function ($user) {
            Post::factory(3)
                ->sequence(
                    [
                        'content' => 'Olá pessoal! Esse é meu primeiro post no Facebug! 😊',
                    ],
                    [
                        'content' => 'Que dia lindo hoje! ☀️ #BomDia',
                    ],
                    [
                        'content' => 'Alguém mais está animado com o novo projeto? 🚀',
                    ],
                )
                ->create(['user_id' => $user->id]);
        });

        // Criar comentários nos posts
        Post::all()->each(function ($post) use ($users) {
            Comment::factory(rand(1, 5))
                ->sequence(
                    [
                        'content' => 'Muito legal! 👏',
                    ],
                    [
                        'content' => 'Parabéns! Continue assim! 🎉',
                    ],
                    [
                        'content' => 'Adorei a ideia! 💡',
                    ],
                    [
                        'content' => 'Também estou ansioso! 😃',
                    ],
                    [
                        'content' => 'Que massa! 🙌',
                    ],
                )
                ->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                ]);
        });

        // Adicionar likes aleatórios
        Post::all()->each(function ($post) use ($users) {
            $randomUsers = $users->random(rand(1, 5));
            $randomUsers->each(function ($user) use ($post) {
                Like::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ]);
            });
        });
    }
}
