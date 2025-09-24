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
        // Lista de usuários predefinidos
        $predefinedUsers = [
            [
                'name' => 'Elian Silva',
                'email' => 'elian@example.com',
            ],
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
        ];

        $users = collect();

        // Criar ou atualizar usuários predefinidos
        foreach ($predefinedUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
            $users->push($user);
        }

        // Adicionar mais alguns usuários aleatórios
        $additionalUsers = User::factory(5)->create();
        $users = $users->merge($additionalUsers);

        // Limpar e criar posts para cada usuário
        Post::whereIn('user_id', $users->pluck('id'))->delete();
        $users->each(function ($user) {
            Post::factory(3)
                ->sequence(
                    [
                        'title' => 'Minha primeira postagem! 🎉',
                        'content' => 'Olá pessoal! Esse é meu primeiro post no Facebug! 😊',
                    ],
                    [
                        'title' => 'Bom dia para todos! ☀️',
                        'content' => 'Que dia lindo hoje! ☀️ #BomDia',
                    ],
                    [
                        'title' => 'Novo projeto em andamento',
                        'content' => 'Alguém mais está animado com o novo projeto? 🚀',
                    ],
                )
                ->create(['user_id' => $user->id]);
        });

        // Limpar e criar comentários nos posts
        Comment::whereIn('post_id', Post::whereIn('user_id', $users->pluck('id'))->pluck('id'))->delete();
        Post::whereIn('user_id', $users->pluck('id'))->each(function ($post) use ($users) {
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

        // Limpar e adicionar likes aleatórios
        Like::whereIn('post_id', Post::whereIn('user_id', $users->pluck('id'))->pluck('id'))->delete();
        Post::whereIn('user_id', $users->pluck('id'))->each(function ($post) use ($users) {
            $randomUsers = $users->random(rand(1, 5));
            $randomUsers->each(function ($user) use ($post) {
                Like::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                    ],
                    []
                );
            });
        });
    }
}
