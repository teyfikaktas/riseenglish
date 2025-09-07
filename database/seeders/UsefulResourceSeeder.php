<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UsefulResource;

class UsefulResourceSeeder extends Seeder
{
    public function run()
    {
        $resources = [
            // TENSES
            [
                'title' => 'Future Perfect Tense',
                'description' => 'Gelecek mükemmel zaman anlatımı ve kullanımı',
                'file_name' => 'future perfect tense.docx',
                'category' => 'tenses',
                'sort_order' => 1,
                'is_popular' => true
            ],
            [
                'title' => 'Future Perfect Continuous Tense',
                'description' => 'Gelecek mükemmel sürekli zaman',
                'file_name' => 'future perfect continuous tense.docx',
                'category' => 'tenses',
                'sort_order' => 2
            ],
            [
                'title' => 'Future Perfect vs Future Perfect Continuous',
                'description' => 'Gelecek zamanların karşılaştırması',
                'file_name' => 'future perfect vs future perfect continuous.docx',
                'category' => 'tenses',
                'sort_order' => 3
            ],
            [
                'title' => 'Past Perfect Tense',
                'description' => 'Geçmiş mükemmel zaman',
                'file_name' => 'PAST PERFECT.docx',
                'category' => 'tenses',
                'sort_order' => 4,
                'is_popular' => true
            ],
            [
                'title' => 'Past Perfect Continuous',
                'description' => 'Geçmiş mükemmel sürekli zaman',
                'file_name' => 'Past Perfect Continuous.docx',
                'category' => 'tenses',
                'sort_order' => 5
            ],
            [
                'title' => 'Past Perfect vs Past Perfect Continuous',
                'description' => 'Geçmiş zamanların karşılaştırması',
                'file_name' => 'PAST PERFECT VE PAST PERFECT CONTINUOUS.docx',
                'category' => 'tenses',
                'sort_order' => 6
            ],
            [
                'title' => 'Past Simple Tense',
                'description' => 'Geçmiş basit zaman',
                'file_name' => 'PAST SIMPLE TENSE.docx',
                'category' => 'tenses',
                'sort_order' => 7
            ],
            [
                'title' => 'Present Perfect',
                'description' => 'Şimdiki mükemmel zaman',
                'file_name' => 'Present Perfect.docx',
                'category' => 'tenses',
                'sort_order' => 8,
                'is_popular' => true
            ],
            [
                'title' => 'Present Continuous Tense',
                'description' => 'Şimdiki sürekli zaman',
                'file_name' => 'Present Continuous Tense Nedir.docx',
                'category' => 'tenses',
                'sort_order' => 9
            ],
            [
                'title' => 'Present Perfect ile Present Perfect Continuous',
                'description' => 'Present perfect zamanların karşılaştırması',
                'file_name' => 'Preset Perfect ile Present Perfect Continuous.docx',
                'category' => 'tenses',
                'sort_order' => 10
            ],

            // MODAL VERBS
            [
                'title' => 'Should Ought To',
                'description' => 'Should ve ought to modal fiilleri',
                'file_name' => 'should ought to .docx',
                'category' => 'modals',
                'sort_order' => 1
            ],
            [
                'title' => 'Have To Must',
                'description' => 'Have to ve must modal fiilleri ve farkları',
                'file_name' => 'have to must farkı.docx',
                'category' => 'modals',
                'sort_order' => 2,
                'is_popular' => true
            ],
            [
                'title' => 'Have To Tüm Zamanlar',
                'description' => 'Have to yapısının tüm zamanlarda kullanımı',
                'file_name' => 'have to tüm zamanlar.docx',
                'category' => 'modals',
                'sort_order' => 3
            ],
            [
                'title' => 'Can Should Must Have To',
                'description' => 'Temel modal fiiller',
                'file_name' => 'can should must have to.docx',
                'category' => 'modals',
                'sort_order' => 4
            ],
            [
                'title' => 'Perfect Tense Modals',
                'description' => 'Mükemmel zamanlarda modal fiiller',
                'file_name' => 'Perfect Tense Modals Nedir.docx',
                'category' => 'modals',
                'sort_order' => 5
            ],
            [
                'title' => 'Passive Form of Perfect Modals',
                'description' => 'Mükemmel modal fiillerin pasif hali',
                'file_name' => 'Passive Form of Perfect Modals.docx',
                'category' => 'modals',
                'sort_order' => 6
            ],

            // ADJECTIVES & ADVERBS
            [
                'title' => 'Possessive Adjectives',
                'description' => 'Sahiplik sıfatları (my, your, his, her...)',
                'file_name' => 'possessive adjectives- sahiplik sıfatları.docx',
                'category' => 'adjectives',
                'sort_order' => 1,
                'is_popular' => true
            ],
            [
                'title' => 'Superlative',
                'description' => 'Üstünlük derecesi sıfatları',
                'file_name' => 'superlative.docx',
                'category' => 'adjectives',
                'sort_order' => 2
            ],
            [
                'title' => 'Comparative',
                'description' => 'Karşılaştırma sıfatları',
                'file_name' => 'Comparative nedir.docx',
                'category' => 'adjectives',
                'sort_order' => 3
            ],

            // PRONOUNS
            [
                'title' => 'Object Pronouns',
                'description' => 'Nesne zamirleri (me, you, him, her...)',
                'file_name' => 'Object Pronoun Nedir.docx',
                'category' => 'pronouns',
                'sort_order' => 1
            ],
            [
                'title' => 'Reflexive Pronouns',
                'description' => 'Dönüşlü zamirler (myself, yourself...)',
                'file_name' => 'Reflexive Pronouns Nedir.docx',
                'category' => 'pronouns',
                'sort_order' => 2
            ],

            // ADVANCED GRAMMAR
            [
                'title' => 'Quantifiers',
                'description' => 'Miktar belirteçleri (some, any, much, many...)',
                'file_name' => 'Quantifiers.docx',
                'category' => 'grammar',
                'sort_order' => 1,
                'is_popular' => true
            ],
            [
                'title' => 'Relative Clauses',
                'description' => 'İlgi cümleleri',
                'file_name' => 'relative clause.docx',
                'category' => 'grammar',
                'sort_order' => 2
            ],
            [
                'title' => 'Relative Clause Reduction',
                'description' => 'İlgi cümlelerinin kısaltılması',
                'file_name' => 'relative clause reduction.docx',
                'category' => 'grammar',
                'sort_order' => 3
            ],
            [
                'title' => 'Causatives',
                'description' => 'Ettirgen yapılar',
                'file_name' => 'Causatives.docx',
                'category' => 'grammar',
                'sort_order' => 4
            ],
            [
                'title' => 'Gerund',
                'description' => 'Ulaç yapısı',
                'file_name' => 'gerund.docx',
                'category' => 'grammar',
                'sort_order' => 5
            ],
            [
                'title' => 'Infinitive',
                'description' => 'Mastar yapısı',
                'file_name' => 'infinitive.docx',
                'category' => 'grammar',
                'sort_order' => 6
            ],
            [
                'title' => 'Subject Gerund',
                'description' => 'Özne olarak gerund kullanımı',
                'file_name' => 'subject gerund.docx',
                'category' => 'grammar',
                'sort_order' => 7
            ],
            [
                'title' => 'Cleft Sentences',
                'description' => 'Bölünmüş cümleler',
                'file_name' => 'Cleft.docx',
                'category' => 'grammar',
                'sort_order' => 8
            ],
            [
                'title' => 'Conjunction Reduction',
                'description' => 'Bağlaç indirgemeleri',
                'file_name' => 'Conjunction Reduction Nedir.docx',
                'category' => 'grammar',
                'sort_order' => 9
            ],
            [
                'title' => 'Ellipsis ve Substitution',
                'description' => 'Eksiltili cümleler ve yerine koyma',
                'file_name' => 'Ellipsis ve Substitution.docx',
                'category' => 'grammar',
                'sort_order' => 10
            ],
            [
                'title' => 'Nominalization',
                'description' => 'İsimleştirme',
                'file_name' => 'Nominalisation Nedir.docx',
                'category' => 'grammar',
                'sort_order' => 11
            ],

            // CONDITIONALS & WISH
            [
                'title' => 'If Type 0',
                'description' => 'Sıfırıncı tip koşul cümleleri',
                'file_name' => 'IF TYPE 0.docx',
                'category' => 'conditionals',
                'sort_order' => 1
            ],
            [
                'title' => 'If Type 1',
                'description' => 'Birinci tip koşul cümleleri',
                'file_name' => 'if type 1.docx',
                'category' => 'conditionals',
                'sort_order' => 2
            ],
            [
                'title' => 'Type 2',
                'description' => 'İkinci tip koşul cümleleri',
                'file_name' => 'type 2.docx',
                'category' => 'conditionals',
                'sort_order' => 3
            ],
            [
                'title' => 'Type 3',
                'description' => 'Üçüncü tip koşul cümleleri',
                'file_name' => 'type 3.docx',
                'category' => 'conditionals',
                'sort_order' => 4
            ],
            [
                'title' => 'Type Mix',
                'description' => 'Karışık tip koşul cümleleri',
                'file_name' => 'type mix.docx',
                'category' => 'conditionals',
                'sort_order' => 5
            ],
            [
                'title' => 'Wish Present and Past',
                'description' => 'Şimdiki ve geçmiş zamanda dilek cümleleri',
                'file_name' => 'Wish Present And Past.docx',
                'category' => 'conditionals',
                'sort_order' => 6
            ],

            // VOCABULARY & EXPRESSIONS
            [
                'title' => 'Question Words',
                'description' => 'Soru kelimeleri (what, where, when...)',
                'file_name' => 'Question Words Nedir.docx',
                'category' => 'vocabulary',
                'sort_order' => 1
            ],
            [
                'title' => 'Question Tag',
                'description' => 'Soru kuyrukları',
                'file_name' => 'Question Tag Nedir.docx',
                'category' => 'vocabulary',
                'sort_order' => 2,
                'is_popular' => true
            ],
            [
                'title' => 'Prepositions',
                'description' => 'Edatlar (in, on, at...)',
                'file_name' => 'preps.docx',
                'category' => 'vocabulary',
                'sort_order' => 3
            ],
            [
                'title' => 'Used To vs Would',
                'description' => 'Geçmiş alışkanlık ifadeleri',
                'file_name' => 'used to -would geçmiş alışkanlık.docx',
                'category' => 'vocabulary',
                'sort_order' => 4
            ],
            [
                'title' => 'Too vs Enough',
                'description' => 'Too ve enough kullanımı',
                'file_name' => 'too sıfat to verb1.docx',
                'category' => 'vocabulary',
                'sort_order' => 5
            ],
            [
                'title' => 'So That vs Such That',
                'description' => 'Sonuç bildiren ifadeler',
                'file_name' => 'so that such that.docx',
                'category' => 'vocabulary',
                'sort_order' => 6
            ],
            [
                'title' => 'Like vs Love vs Hate to vs Verb-ing',
                'description' => 'Sevme ve sevmeme ifadeleri',
                'file_name' => 'like love hate to verb1  verb ing.docx',
                'category' => 'vocabulary',
                'sort_order' => 7
            ],

            // SPECIFIC TOPICS
            [
                'title' => 'Singular vs Plural',
                'description' => 'Tekil ve çoğul kullanımı',
                'file_name' => 'singular plural.docx',
                'category' => 'grammar-basics',
                'sort_order' => 1
            ],
            [
                'title' => 'Sıfat ve Zarflar',
                'description' => 'Sıfat ve zarf kullanımı',
                'file_name' => 'sıfat ve zarflar.docx',
                'category' => 'grammar-basics',
                'sort_order' => 2
            ],
            [
                'title' => 'Demonstratives',
                'description' => 'İşaret sıfatları (this, that, these, those)',
                'file_name' => 'DEMONSTRATIVES.docx',
                'category' => 'grammar-basics',
                'sort_order' => 3
            ],
            [
                'title' => 'Imperative (Emir Cümleleri)',
                'description' => 'Emir kipi cümleleri',
                'file_name' => 'imperative emir cümlesi.docx',
                'category' => 'grammar-basics',
                'sort_order' => 4
            ],
            [
                'title' => 'In On At',
                'description' => 'Yer ve zaman edatları',
                'file_name' => 'in on at.docx',
                'category' => 'grammar-basics',
                'sort_order' => 5
            ],
            [
                'title' => 'A vs An vs The',
                'description' => 'Belirsiz ve belirli artikeller',
                'file_name' => 'a an the.docx',
                'category' => 'grammar-basics',
                'sort_order' => 6
            ],

            // ADVANCED EXPRESSIONS
            [
                'title' => 'Either...or Neither...nor Both...and',
                'description' => 'Bağlantı ifadeleri',
                'file_name' => 'Either ... or neither ...nor  both ... and.docx',
                'category' => 'advanced',
                'sort_order' => 1
            ],
            [
                'title' => 'Every vs Each Farkı',
                'description' => 'Every ve each arasındaki farklar',
                'file_name' => 'Every ve Each farkı.docx',
                'category' => 'advanced',
                'sort_order' => 2
            ],
            [
                'title' => 'So vs As...as The same...as',
                'description' => 'Karşılaştırma ifadeleri',
                'file_name' => 'So as As....as The same....as.docx',
                'category' => 'advanced',
                'sort_order' => 3
            ],
            [
                'title' => 'Sometime Every time No time Any time',
                'description' => 'Zaman ifadeleri',
                'file_name' => 'Sometime Every time No time Any time....docx',
                'category' => 'advanced',
                'sort_order' => 4
            ],
            [
                'title' => 'Somewhere',
                'description' => 'Yer belirten ifadeler',
                'file_name' => 'Somewhere.docx',
                'category' => 'advanced',
                'sort_order' => 5
            ],
            [
                'title' => 'Other Another',
                'description' => 'Other ve another kullanımı',
                'file_name' => 'Other another.docx',
                'category' => 'advanced',
                'sort_order' => 6
            ],
            [
                'title' => 'The More The More',
                'description' => 'Karşılaştırmalı artış ifadeleri',
                'file_name' => 'the more the more.docx',
                'category' => 'advanced',
                'sort_order' => 7
            ],
            [
                'title' => 'There IS There Are',
                'description' => 'Var olmayı belirten yapılar',
                'file_name' => 'THERE IS there are.docx',
                'category' => 'advanced',
                'sort_order' => 8
            ],
            [
                'title' => 'There WAS There Were',
                'description' => 'Geçmişte var olmayı belirten yapılar',
                'file_name' => 'THERE WAS there were .docx',
                'category' => 'advanced',
                'sort_order' => 9
            ],
            [
                'title' => 'Thingler',
                'description' => 'Çeşitli konular',
                'file_name' => 'thingler .docx',
                'category' => 'advanced',
                'sort_order' => 10
            ],

            // SPECIAL TOPICS
            [
                'title' => 'Have Got vs Have Had',
                'description' => 'Have got ve have had yapıları',
                'file_name' => 'HAVE GOT has got have had will have to....docx',
                'category' => 'special',
                'sort_order' => 1
            ],
            [
                'title' => 'Gelecek Zaman',
                'description' => 'Gelecek zaman anlatımı',
                'file_name' => 'Gelecek Zaman Nedir.docx',
                'category' => 'special',
                'sort_order' => 2
            ],
            [
                'title' => 'Geniş Zamanda Sıklık Zarfları',
                'description' => 'Sıklık zarflarının kullanımı',
                'file_name' => 'Geniş Zamanda Sıklık Zarfları.docx',
                'category' => 'special',
                'sort_order' => 3
            ],
            [
                'title' => 'İngilizce Temel Bilgiler',
                'description' => 'İngilizce temel konular',
                'file_name' => 'İngilizce.docx',
                'category' => 'special',
                'sort_order' => 4
            ],
            [
                'title' => 'Mek Mak İçin So As To',
                'description' => 'Amaç ifadeleri',
                'file_name' => 'mek mak için so as to.docx',
                'category' => 'special',
                'sort_order' => 5
            ],
            [
                'title' => 'Yes No Questions',
                'description' => 'Evet hayır soruları',
                'file_name' => 'yes no questions.docx',
                'category' => 'special',
                'sort_order' => 6
            ],

            // SUFFIXES & WORD FORMATION
            [
                'title' => '-ed, -ing Sıfatları',
                'description' => 'Ed ve ing ile biten sıfatlar',
                'file_name' => '-ed, -ing sıfatlar.docx',
                'category' => 'word-formation',
                'sort_order' => 1
            ],

            // COUNTING & NUMBERS
            [
                'title' => 'Sayılabilen ve Sayılamayan',
                'description' => 'Countable ve uncountable nouns',
                'file_name' => 'Sayılabilen (Countable) & Sayılamayan (....docx',
                'category' => 'vocabulary',
                'sort_order' => 8
            ],

            // BODY & DESCRIPTION
            [
                'title' => 'Body ve One ile Biten Kelimeler',
                'description' => 'Body ve one ile oluşturulan kelimeler',
                'file_name' => 'Body ve One ile biten kelimeler.docx',
                'category' => 'vocabulary',
                'sort_order' => 9
            ],

            // FORMATION RULES
            [
                'title' => 'By Kalıpları',
                'description' => 'By ile oluşturulan kalıplar',
                'file_name' => 'BY KALIPLARI.docx',
                'category' => 'patterns',
                'sort_order' => 1
            ],

            // SENTENCE COMPLETION
            [
                'title' => 'Cümle Sonu Too Also As Well',
                'description' => 'Cümle sonunda da anlamı veren kelimeler',
                'file_name' => 'Cümle sonu too also as well.docx',
                'category' => 'patterns',
                'sort_order' => 2
            ]
        ];

        foreach ($resources as $resource) {
            $filePath = 'useful-resources/' . $resource['file_name'];
            
            UsefulResource::create([
                'title' => $resource['title'],
                'description' => $resource['description'] ?? '',
                'file_path' => $filePath,
                'file_name' => $resource['file_name'],
                'file_type' => 'docx',
                'file_size' => rand(2300000, 2400000), // 2.3-2.4MB arası
                'category' => $resource['category'],
                'sort_order' => $resource['sort_order'] ?? 0,
                'is_popular' => $resource['is_popular'] ?? false,
                'view_count' => rand(5, 150),
                'download_count' => rand(1, 75),
                'is_active' => true
            ]);
        }

        // Log the completion
        $this->command->info('Created ' . count($resources) . ' useful resources successfully!');
        
        // Show categories summary
        $categories = collect($resources)->groupBy('category');
        $this->command->info("\nCategories created:");
        foreach ($categories as $category => $items) {
            $this->command->info("- {$category}: " . count($items) . " items");
        }
    }
}