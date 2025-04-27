<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TopicCategory;
use App\Models\Topic;

class TopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Grammar-Reading category
        $grammarReading = TopicCategory::create([
            'name' => 'Grammar-Reading',
            'description' => 'Grammar and reading topics',
            'order' => 1,
            'is_active' => true,
        ]);

        // Create Speaking category
        $speaking = TopicCategory::create([
            'name' => 'Speaking',
            'description' => 'Speaking topics',
            'order' => 2,
            'is_active' => true,
        ]);

        // Create Listening category
        $listening = TopicCategory::create([
            'name' => 'Listening',
            'description' => 'Listening topics',
            'order' => 3,
            'is_active' => true,
        ]);

        // Create Writing category
        $writing = TopicCategory::create([
            'name' => 'Writing',
            'description' => 'Writing topics',
            'order' => 4,
            'is_active' => true,
        ]);

        // Add topics to Grammar-Reading
        $topics = [
            [
                'name' => 'ÖFNYZ',
                'description' => 'Özne, fiil, nesne, yer, zaman',
                'level' => 'A1',
                'order' => 1,
            ],
            [
                'name' => 'To be (am was will be)',
                'description' => 'To be verb in different tenses',
                'level' => 'A1',
                'order' => 2,
            ],
            [
                'name' => 'Önemli prepositions',
                'description' => 'Important prepositions in English',
                'level' => 'A1',
                'order' => 3,
            ],
            [
                'name' => 'There is/was/will be',
                'description' => 'There is/was/will be structures',
                'level' => 'A1',
                'order' => 4,
            ],
        ];

        foreach ($topics as $topicData) {
            Topic::create(array_merge($topicData, [
                'topic_category_id' => $grammarReading->id,
                'is_active' => true,
            ]));
        }

        // Add some example topics to other categories
        // Speaking
        Topic::create([
            'topic_category_id' => $speaking->id,
            'name' => 'Introduction and Greetings',
            'description' => 'How to introduce yourself and greet others',
            'level' => 'A1',
            'order' => 1,
            'is_active' => true,
        ]);

        Topic::create([
            'topic_category_id' => $speaking->id,
            'name' => 'Personal Information',
            'description' => 'Talking about personal information',
            'level' => 'A1',
            'order' => 2,
            'is_active' => true,
        ]);

        // Listening
        Topic::create([
            'topic_category_id' => $listening->id,
            'name' => 'Basic Instructions',
            'description' => 'Understanding basic instructions',
            'level' => 'A1',
            'order' => 1,
            'is_active' => true,
        ]);

        Topic::create([
            'topic_category_id' => $listening->id,
            'name' => 'Numbers and Time',
            'description' => 'Understanding numbers and time expressions',
            'level' => 'A1',
            'order' => 2,
            'is_active' => true,
        ]);

        // Writing
        Topic::create([
            'topic_category_id' => $writing->id,
            'name' => 'Simple Sentences',
            'description' => 'Writing simple sentences',
            'level' => 'A1',
            'order' => 1,
            'is_active' => true,
        ]);

        Topic::create([
            'topic_category_id' => $writing->id,
            'name' => 'Forms and Applications',
            'description' => 'Filling out forms and applications',
            'level' => 'A1',
            'order' => 2,
            'is_active' => true,
        ]);
    }
}