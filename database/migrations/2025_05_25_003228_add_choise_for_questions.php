<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Choices tablosunu oluştur (eğer yoksa)
        if (!Schema::hasTable('choices')) {
            Schema::create('choices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->string('choice_letter', 1); // A, B, C, D, E
                $table->text('choice_text');
                $table->boolean('is_correct')->default(false);
                $table->text('explanation')->nullable();
                $table->integer('order_number')->default(0);
                $table->timestamps();

                $table->index(['question_id', 'order_number']);
                $table->index(['question_id', 'is_correct']);
            });
        }

        // Mevcut choices verilerini temizle
        DB::table('choices')->delete();

        // 36 soru için choices ekleme
        $questionsChoices = [
            // Soru 1
            1 => [
                ['A', 'since', false],
                ['B', 'as soon as', true],
                ['C', 'whenever', false],
                ['D', 'as long as', false],
                ['E', 'while', false]
            ],
            // Soru 2
            2 => [
                ['A', 'while', false],
                ['B', 'unless', false],
                ['C', 'before', false],
                ['D', 'since', false],
                ['E', 'once', true]
            ],
            // Soru 3
            3 => [
                ['A', 'in case', true],
                ['B', 'although', false],
                ['C', 'once', false],
                ['D', 'so that', false],
                ['E', 'as if', false]
            ],
            // Soru 4
            4 => [
                ['A', 'as long as', false],
                ['B', 'even if', true],
                ['C', 'now that', false],
                ['D', 'so that', false],
                ['E', 'unless', false]
            ],
            // Soru 5
            5 => [
                ['A', 'Just as', false],
                ['B', 'In case', false],
                ['C', 'As long as', false],
                ['D', 'Much as', true],
                ['E', 'Now that', false]
            ],
            // Soru 6
            6 => [
                ['A', 'so that', false],
                ['B', 'in case', false],
                ['C', 'with the aim of', true],
                ['D', 'even though', false],
                ['E', 'now that', false]
            ],
            // Soru 7
            7 => [
                ['A', 'Even though', false],
                ['B', 'Despite', false],
                ['C', 'Unless', false],
                ['D', 'Because', false],
                ['E', 'However', true]
            ],
            // Soru 8
            8 => [
                ['A', 'But for', true],
                ['B', 'Even though', false],
                ['C', 'In case', false],
                ['D', 'As well as', false],
                ['E', 'So that', false]
            ],
            // Soru 9
            9 => [
                ['A', 'except for', false],
                ['B', 'contrary to', true],
                ['C', 'due to', false],
                ['D', 'along with', false],
                ['E', 'regardless of', false]
            ],
            // Soru 10
            10 => [
                ['A', 'even though', false],
                ['B', 'in case of', false],
                ['C', 'thanks to', true],
                ['D', 'regardless of', false],
                ['E', 'rather than', false]
            ],
            // Soru 11
            11 => [
                ['A', 'whereas', false],
                ['B', 'so that', false],
                ['C', 'in case', false],
                ['D', 'thereby', true],
                ['E', 'despite', false]
            ],
            // Soru 12
            12 => [
                ['A', 'unless', false],
                ['B', 'so long as', false],
                ['C', 'as if', false],
                ['D', 'even though', false],
                ['E', 'inasmuch as', true]
            ],
            // Soru 13
            13 => [
                ['A', 'despite', true],
                ['B', 'unless', false],
                ['C', 'whereas', false],
                ['D', 'in order that', false],
                ['E', 'because of', false]
            ],
            // Soru 14
            14 => [
                ['A', 'despite', false],
                ['B', 'even if', false],
                ['C', 'as though', false],
                ['D', 'provided that', false],
                ['E', 'hence', true]
            ],
            // Soru 15
            15 => [
                ['A', 'nevertheless', true],
                ['B', 'because', false],
                ['C', 'inasmuch as', false],
                ['D', 'as', false],
                ['E', 'as a result', false]
            ],
            // Soru 16
            16 => [
                ['A', 'despite', false],
                ['B', 'instead', true],
                ['C', 'rather than', false],
                ['D', 'however', false],
                ['E', 'in fact', false]
            ],
            // Soru 17
            17 => [
                ['A', 'as though', false],
                ['B', 'in case of', false],
                ['C', 'irrespective of', true],
                ['D', 'provided that', false],
                ['E', 'owing to', false]
            ],
            // Soru 18
            18 => [
                ['A', 'provided that', false],
                ['B', 'in contrast', false],
                ['C', 'even so', false],
                ['D', 'consequently', true],
                ['E', 'as if', false]
            ],
            // Soru 19
            19 => [
                ['A', 'instead', false],
                ['B', 'likewise', true],
                ['C', 'lest', false],
                ['D', 'whereas', false],
                ['E', 'however', false]
            ],
            // Soru 20
            20 => [
                ['A', 'therefore', false],
                ['B', 'even though', false],
                ['C', 'on the other hand', true],
                ['D', 'due to', false],
                ['E', 'as a result', false]
            ],
            // Soru 21
            21 => [
                ['A', 'in that', true],
                ['B', 'even if', false],
                ['C', 'unless', false],
                ['D', 'rather than', false],
                ['E', 'instead of', false]
            ],
            // Soru 22
            22 => [
                ['A', 'consequently', false],
                ['B', 'otherwise', false],
                ['C', 'though', false],
                ['D', 'in case', false],
                ['E', 'meanwhile', true]
            ],
            // Soru 23
            23 => [
                ['A', 'although', false],
                ['B', 'unless', false],
                ['C', 'on the contrary', false],
                ['D', 'regardless of', false],
                ['E', 'in addition', true]
            ],
            // Soru 24
            24 => [
                ['A', 'because', false],
                ['B', 'notwithstanding', true],
                ['C', 'unless', false],
                ['D', 'similarly', false],
                ['E', 'as though', false]
            ],
            // Soru 25
            25 => [
                ['A', 'hence', false],
                ['B', 'yet', true],
                ['C', 'as a result', false],
                ['D', 'in that', false],
                ['E', 'unless', false]
            ],
            // Soru 26
            26 => [
                ['A', 'apart from', true],
                ['B', 'because of', false],
                ['C', 'moreover', false],
                ['D', 'in terms of', false],
                ['E', 'regardless of', false]
            ],
            // Soru 27
            27 => [
                ['A', 'instead', false],
                ['B', 'as a result', false],
                ['C', 'nevertheless', false],
                ['D', 'even if', false],
                ['E', 'to illustrate', true]
            ],
            // Soru 28
            28 => [
                ['A', 'Unless', false],
                ['B', 'Just as', true],
                ['C', 'In case', false],
                ['D', 'Even if', false],
                ['E', 'Rather than', false]
            ],
            // Soru 29
            29 => [
                ['A', 'as if', false],
                ['B', 'owing to', false],
                ['C', 'in that', false],
                ['D', 'while', true],
                ['E', 'unless', false]
            ],
            // Soru 30
            30 => [
                ['A', 'so', false],
                ['B', 'even if', false],
                ['C', 'for fear that', true],
                ['D', 'as long as', false],
                ['E', 'now that', false]
            ],
            // Soru 31
            31 => [
                ['A', 'unless', false],
                ['B', 'as soon as', false],
                ['C', 'by the time', true],
                ['D', 'even though', false],
                ['E', 'in case', false]
            ],
            // Soru 32
            32 => [
                ['A', 'otherwise', true],
                ['B', 'even so', false],
                ['C', 'in fact', false],
                ['D', 'provided that', false],
                ['E', 'in spite of', false]
            ],
            // Soru 33
            33 => [
                ['A', 'in contrast', false],
                ['B', 'in brief', true],
                ['C', 'for example', false],
                ['D', 'on the other hand', false],
                ['E', 'even though', false]
            ],
            // Soru 34
            34 => [
                ['A', 'in spite of', false],
                ['B', 'regardless of', false],
                ['C', 'in case of', false],
                ['D', 'contrary to', false],
                ['E', 'except for', true]
            ],
            // Soru 35
            35 => [
                ['A', 'however', false],
                ['B', 'thus', false],
                ['C', 'instead', false],
                ['D', 'namely', true],
                ['E', 'in contrast', false]
            ],
            // Soru 36
            36 => [
                ['A', 'nevertheless', false],
                ['B', 'indeed', true],
                ['C', 'on the contrary', false],
                ['D', 'otherwise', false],
                ['E', 'even though', false]
            ]
        ];

        // Her soru için choices ekle
        foreach ($questionsChoices as $questionId => $choices) {
            foreach ($choices as $index => $choice) {
                DB::table('choices')->insert([
                    'question_id' => $questionId,
                    'choice_letter' => $choice[0],
                    'choice_text' => $choice[1],
                    'is_correct' => $choice[2],
                    'order_number' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('choices');
    }
};
      