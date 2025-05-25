<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Önce sütunları ekle
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'options')) {
                $table->json('options')->nullable()->after('question_text');
            }
            if (!Schema::hasColumn('questions', 'correct_answer')) {
                $table->string('correct_answer', 1)->nullable()->after('options');
            }
        });

        // Mevcut soruları güncelle - Migration sırasına göre ID'ler
        $questionsData = [
            // Test 1 Soruları (ID: 1-12)
            1 => [
                'options' => ['A' => 'since', 'B' => 'as soon as', 'C' => 'whenever', 'D' => 'as long as', 'E' => 'while'],
                'correct_answer' => 'B'
            ],
            2 => [
                'options' => ['A' => 'while', 'B' => 'unless', 'C' => 'before', 'D' => 'since', 'E' => 'once'],
                'correct_answer' => 'E'
            ],
            3 => [
                'options' => ['A' => 'in case', 'B' => 'although', 'C' => 'once', 'D' => 'so that', 'E' => 'as if'],
                'correct_answer' => 'A'
            ],
            4 => [
                'options' => ['A' => 'as long as', 'B' => 'even if', 'C' => 'now that', 'D' => 'so that', 'E' => 'unless'],
                'correct_answer' => 'B'
            ],
            5 => [
                'options' => ['A' => 'Just as', 'B' => 'In case', 'C' => 'As long as', 'D' => 'Much as', 'E' => 'Now that'],
                'correct_answer' => 'D'
            ],
            6 => [
                'options' => ['A' => 'so that', 'B' => 'in case', 'C' => 'with the aim of', 'D' => 'even though', 'E' => 'now that'],
                'correct_answer' => 'C'
            ],
            7 => [
                'options' => ['A' => 'Even though', 'B' => 'Despite', 'C' => 'Unless', 'D' => 'Because', 'E' => 'However'],
                'correct_answer' => 'E'
            ],
            8 => [
                'options' => ['A' => 'But for', 'B' => 'Even though', 'C' => 'In case', 'D' => 'As well as', 'E' => 'So that'],
                'correct_answer' => 'A'
            ],
            9 => [
                'options' => ['A' => 'except for', 'B' => 'contrary to', 'C' => 'due to', 'D' => 'along with', 'E' => 'regardless of'],
                'correct_answer' => 'B'
            ],
            10 => [
                'options' => ['A' => 'even though', 'B' => 'in case of', 'C' => 'thanks to', 'D' => 'regardless of', 'E' => 'rather than'],
                'correct_answer' => 'C'
            ],
            11 => [
                'options' => ['A' => 'whereas', 'B' => 'so that', 'C' => 'in case', 'D' => 'thereby', 'E' => 'despite'],
                'correct_answer' => 'D'
            ],
            12 => [
                'options' => ['A' => 'unless', 'B' => 'so long as', 'C' => 'as if', 'D' => 'even though', 'E' => 'inasmuch as'],
                'correct_answer' => 'E'
            ],
            
            // Test 2 Soruları (ID: 13-24)
            13 => [
                'options' => ['A' => 'despite', 'B' => 'unless', 'C' => 'whereas', 'D' => 'in order that', 'E' => 'because of'],
                'correct_answer' => 'A'
            ],
            14 => [
                'options' => ['A' => 'despite', 'B' => 'even if', 'C' => 'as though', 'D' => 'provided that', 'E' => 'hence'],
                'correct_answer' => 'E'
            ],
            15 => [
                'options' => ['A' => 'nevertheless', 'B' => 'because', 'C' => 'inasmuch as', 'D' => 'as', 'E' => 'as a result'],
                'correct_answer' => 'A'
            ],
            16 => [
                'options' => ['A' => 'despite', 'B' => 'instead', 'C' => 'rather than', 'D' => 'however', 'E' => 'in fact'],
                'correct_answer' => 'B'
            ],
            17 => [
                'options' => ['A' => 'as though', 'B' => 'in case of', 'C' => 'irrespective of', 'D' => 'provided that', 'E' => 'owing to'],
                'correct_answer' => 'C'
            ],
            18 => [
                'options' => ['A' => 'provided that', 'B' => 'in contrast', 'C' => 'even so', 'D' => 'consequently', 'E' => 'as if'],
                'correct_answer' => 'D'
            ],
            19 => [
                'options' => ['A' => 'instead', 'B' => 'likewise', 'C' => 'lest', 'D' => 'whereas', 'E' => 'however'],
                'correct_answer' => 'B'
            ],
            20 => [
                'options' => ['A' => 'therefore', 'B' => 'even though', 'C' => 'on the other hand', 'D' => 'due to', 'E' => 'as a result'],
                'correct_answer' => 'C'
            ],
            21 => [
                'options' => ['A' => 'in that', 'B' => 'even if', 'C' => 'unless', 'D' => 'rather than', 'E' => 'instead of'],
                'correct_answer' => 'A'
            ],
            22 => [
                'options' => ['A' => 'consequently', 'B' => 'otherwise', 'C' => 'though', 'D' => 'in case', 'E' => 'meanwhile'],
                'correct_answer' => 'E'
            ],
            23 => [
                'options' => ['A' => 'although', 'B' => 'unless', 'C' => 'on the contrary', 'D' => 'regardless of', 'E' => 'in addition'],
                'correct_answer' => 'E'
            ],
            24 => [
                'options' => ['A' => 'because', 'B' => 'notwithstanding', 'C' => 'unless', 'D' => 'similarly', 'E' => 'as though'],
                'correct_answer' => 'B'
            ],
            
            // Test 3 Soruları (ID: 25-36)
            25 => [
                'options' => ['A' => 'hence', 'B' => 'yet', 'C' => 'as a result', 'D' => 'in that', 'E' => 'unless'],
                'correct_answer' => 'B'
            ],
            26 => [
                'options' => ['A' => 'apart from', 'B' => 'because of', 'C' => 'moreover', 'D' => 'in terms of', 'E' => 'regardless of'],
                'correct_answer' => 'A'
            ],
            27 => [
                'options' => ['A' => 'instead', 'B' => 'as a result', 'C' => 'nevertheless', 'D' => 'even if', 'E' => 'to illustrate'],
                'correct_answer' => 'E'
            ],
            28 => [
                'options' => ['A' => 'Unless', 'B' => 'Just as', 'C' => 'In case', 'D' => 'Even if', 'E' => 'Rather than'],
                'correct_answer' => 'B'
            ],
            29 => [
                'options' => ['A' => 'as if', 'B' => 'owing to', 'C' => 'in that', 'D' => 'while', 'E' => 'unless'],
                'correct_answer' => 'D'
            ],
            30 => [
                'options' => ['A' => 'so', 'B' => 'even if', 'C' => 'for fear that', 'D' => 'as long as', 'E' => 'now that'],
                'correct_answer' => 'C'
            ],
            31 => [
                'options' => ['A' => 'unless', 'B' => 'as soon as', 'C' => 'by the time', 'D' => 'even though', 'E' => 'in case'],
                'correct_answer' => 'C'
            ],
            32 => [
                'options' => ['A' => 'otherwise', 'B' => 'even so', 'C' => 'in fact', 'D' => 'provided that', 'E' => 'in spite of'],
                'correct_answer' => 'A'
            ],
            33 => [
                'options' => ['A' => 'in contrast', 'B' => 'in brief', 'C' => 'for example', 'D' => 'on the other hand', 'E' => 'even though'],
                'correct_answer' => 'B'
            ],
            34 => [
                'options' => ['A' => 'in spite of', 'B' => 'regardless of', 'C' => 'in case of', 'D' => 'contrary to', 'E' => 'except for'],
                'correct_answer' => 'E'
            ],
            35 => [
                'options' => ['A' => 'however', 'B' => 'thus', 'C' => 'instead', 'D' => 'namely', 'E' => 'in contrast'],
                'correct_answer' => 'D'
            ],
            36 => [
                'options' => ['A' => 'nevertheless', 'B' => 'indeed', 'C' => 'on the contrary', 'D' => 'otherwise', 'E' => 'even though'],
                'correct_answer' => 'B'
            ]
        ];

        // Her soruyu güncelle
        foreach ($questionsData as $id => $data) {
            DB::table('questions')
                ->where('id', $id)
                ->update([
                    'options' => json_encode($data['options']),
                    'correct_answer' => $data['correct_answer'],
                    'updated_at' => now()
                ]);
        }
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['options', 'correct_answer']);
        });
    }
};