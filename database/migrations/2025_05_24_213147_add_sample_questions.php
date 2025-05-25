<?php
// database/migrations/2024_01_01_000002_seed_test_data.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TestCategory;
use App\Models\Test;
use App\Models\Question;

return new class extends Migration
{
    public function up()
    {
        // Test Kategorilerini Oluştur
        $categories = [
            [
                'name' => 'YDT',
                'slug' => 'ydt',
                'description' => 'YDS benzeri sorularla üniversite sınavlarına hazırlanın.',
                'icon' => '📝',
                'difficulty_level' => 'Orta-Zor',
                'color' => '#1a2e5a',
                'sort_order' => 1
            ],
            [
                'name' => 'YÖKDİL',
                'slug' => 'yokdil',
                'description' => 'Akademik İngilizce sorularıyla YÖKDİL\'e hazırlanın.',
                'icon' => '🎓',
                'difficulty_level' => 'Zor',
                'color' => '#1a2e5a',
                'sort_order' => 2
            ],
            [
                'name' => 'Zamanlar',
                'slug' => 'zamanlar',
                'description' => 'Present, Past, Future ve diğer tüm zamanlar.',
                'icon' => '⏰',
                'difficulty_level' => 'Kolay-Orta',
                'color' => '#1a2e5a',
                'sort_order' => 3
            ],
            [
                'name' => 'Kelime Bilgisi',
                'slug' => 'kelime-bilgisi',
                'description' => 'Kelime dağarcığınızı genişletin ve test edin.',
                'icon' => '📚',
                'difficulty_level' => 'Karma',
                'color' => '#1a2e5a',
                'sort_order' => 4
            ],
            [
                'name' => 'Okuduğunu Anlama',
                'slug' => 'okudugunu-anlama',
                'description' => 'Metin anlama ve yorumlama becerilerinizi geliştirin.',
                'icon' => '📖',
                'difficulty_level' => 'Orta',
                'color' => '#1a2e5a',
                'sort_order' => 5
            ],
            [
                'name' => 'KPDS/ÜDS',
                'slug' => 'kpds-uds',
                'description' => 'KPDS ve ÜDS sınavlarına yönelik özel sorular.',
                'icon' => '🏛️',
                'difficulty_level' => 'Zor',
                'color' => '#1a2e5a',
                'sort_order' => 6
            ]
        ];

        foreach ($categories as $categoryData) {
            TestCategory::create($categoryData);
        }

        // Kategorileri al
        $ydtCategory = TestCategory::where('slug', 'ydt')->first();
        $yokdilCategory = TestCategory::where('slug', 'yokdil')->first();
        $zamanlarnCategory = TestCategory::where('slug', 'zamanlar')->first();

        // Testleri Oluştur
        $tests = [
            [
                'title' => 'YDT Deneme Testi 1',
                'slug' => 'ydt-deneme-1',
                'description' => 'YDT sınavına hazırlık için bağlaç soruları testi.',
                'duration_minutes' => 15,
                'difficulty_level' => 'Orta',
                'question_count' => 12,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'YÖKDİL Deneme Testi 1',
                'slug' => 'yokdil-deneme-1',
                'description' => 'YÖKDİL sınavına hazırlık için bağlaç soruları testi.',
                'duration_minutes' => 18,
                'difficulty_level' => 'Zor',
                'question_count' => 12,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Bağlaç Soruları Testi',
                'slug' => 'bagac-sorulari-1',
                'description' => 'İngilizce bağlaç konularını pekiştiren kapsamlı test.',
                'duration_minutes' => 20,
                'difficulty_level' => 'Orta-Zor',
                'question_count' => 12,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3
            ]
        ];

        foreach ($tests as $testData) {
            Test::create($testData);
        }

        // Test 1 Sorularını Oluştur
        $test1Questions = [
            [
                'question_text' => 'The patient was in a critical condition, so the doctors had to operate immediately. The surgical team rushed to the operating room and began the procedure ____ the final test results were delivered.',
                'options' => ['A' => 'since', 'B' => 'as soon as', 'C' => 'whenever', 'D' => 'as long as', 'E' => 'while'],
                'correct_answer' => 'B',
                'explanation' => '"as soon as" doğru cevaptır çünkü ameliyatın test sonuçları gelmeden önce başladığını belirtir.'
            ],
            [
                'question_text' => 'The security protocol was so sensitive that employees were granted access to the confidential data ____ their identity and clearance level had been verified multiple times through both manual and digital systems.',
                'options' => ['A' => 'while', 'B' => 'unless', 'C' => 'before', 'D' => 'since', 'E' => 'once'],
                'correct_answer' => 'E',
                'explanation' => '"once" doğru cevaptır çünkü kimlik doğrulama tamamlandıktan sonra erişim verildiğini belirtir.'
            ],
            [
                'question_text' => 'The research team installed multiple backup power systems throughout the laboratory ____ an unexpected outage jeopardized the integrity of the long-running experiments requiring uninterrupted data collection.',
                'options' => ['A' => 'in case', 'B' => 'although', 'C' => 'once', 'D' => 'so that', 'E' => 'as if'],
                'correct_answer' => 'A',
                'explanation' => '"in case" doğru cevaptır çünkü olası bir elektrik kesintisine karşı önlem alındığını belirtir.'
            ],
            [
                'question_text' => 'The hikers decided to proceed with the final stage of the climb ____ the weather forecast had indicated a high chance of sudden snowfall and dropping temperatures.',
                'options' => ['A' => 'as long as', 'B' => 'even if', 'C' => 'now that', 'D' => 'so that', 'E' => 'unless'],
                'correct_answer' => 'B',
                'explanation' => '"even if" doğru cevaptır çünkü kötü hava koşullarına rağmen tırmanışa devam etme kararını belirtir.'
            ],
            [
                'question_text' => '____ the government has launched several initiatives to address income inequality, critics argue that the measures fall short of tackling the structural issues that perpetuate social injustice.',
                'options' => ['A' => 'Just as', 'B' => 'In case', 'C' => 'As long as', 'D' => 'Much as', 'E' => 'Now that'],
                'correct_answer' => 'D',
                'explanation' => '"Much as" doğru cevaptır çünkü hükümetin girişimlerine rağmen eleştirilerin devam ettiğini belirtir.'
            ],
            [
                'question_text' => 'The pharmaceutical company invested millions in advanced genetic research technologies ____ developing more personalized treatments for rare autoimmune disorders.',
                'options' => ['A' => 'so that', 'B' => 'in case', 'C' => 'with the aim of', 'D' => 'even though', 'E' => 'now that'],
                'correct_answer' => 'C',
                'explanation' => '"with the aim of" doğru cevaptır çünkü yatırımın amacını belirtir.'
            ],
            [
                'question_text' => '____ meticulously Hippocratic physicians documented symptoms and prescribed treatments, their understanding of disease was still limited by the scientific constraints of their era.',
                'options' => ['A' => 'Even though', 'B' => 'Despite', 'C' => 'Unless', 'D' => 'Because', 'E' => 'However'],
                'correct_answer' => 'E',
                'explanation' => '"However" doğru cevaptır çünkü zıtlık belirten bir bağlaç gerekir.'
            ],
            [
                'question_text' => '____ the protective coral reefs surrounding the island, many marine species would have been exposed to strong currents and predators.',
                'options' => ['A' => 'But for', 'B' => 'Even though', 'C' => 'In case', 'D' => 'As well as', 'E' => 'So that'],
                'correct_answer' => 'A',
                'explanation' => '"But for" doğru cevaptır çünkü mercan resiflerinin koruyucu etkisini vurgular.'
            ],
            [
                'question_text' => 'The court ruled that the defendant\'s actions, ____ his claims of self-defense, demonstrated a clear intent to cause harm.',
                'options' => ['A' => 'except for', 'B' => 'contrary to', 'C' => 'due to', 'D' => 'along with', 'E' => 'regardless of'],
                'correct_answer' => 'B',
                'explanation' => '"contrary to" doğru cevaptır çünkü savunma iddialarının aksine zararlı niyet gösterildiğini belirtir.'
            ],
            [
                'question_text' => 'The revival of traditional folk instruments in contemporary compositions became possible ____ a new wave of ethnomusicologists who dedicated years to preserving endangered musical heritage.',
                'options' => ['A' => 'even though', 'B' => 'in case of', 'C' => 'thanks to', 'D' => 'regardless of', 'E' => 'rather than'],
                'correct_answer' => 'C',
                'explanation' => '"thanks to" doğru cevaptır çünkü etnomuzikolojistlerin katkısını vurgular.'
            ],
            [
                'question_text' => 'The pediatrician advised increasing physical contact during early infancy, ____ strengthening the bond between parent and child and promoting emotional development.',
                'options' => ['A' => 'whereas', 'B' => 'so that', 'C' => 'in case', 'D' => 'thereby', 'E' => 'despite'],
                'correct_answer' => 'D',
                'explanation' => '"thereby" doğru cevaptır çünkü fiziksel temasın sonucunu belirtir.'
            ],
            [
                'question_text' => 'The government\'s response to the earthquake was deemed inadequate ____ it failed to provide timely shelter and medical assistance to thousands left homeless by the disaster.',
                'options' => ['A' => 'unless', 'B' => 'so long as', 'C' => 'as if', 'D' => 'even though', 'E' => 'inasmuch as'],
                'correct_answer' => 'E',
                'explanation' => '"inasmuch as" doğru cevaptır çünkü yetersizliğin nedenini açıklar.'
            ]
        ];

        // Test 2 Sorularını Oluştur (Belgeden alınan ilk 12 soru)
        $test2Questions = [
            [
                'question_text' => 'The architectural precision of the pyramids continues to astonish modern engineers ____ the limited technological resources available to the ancient Egyptians at the time.',
                'options' => ['A' => 'despite', 'B' => 'unless', 'C' => 'whereas', 'D' => 'in order that', 'E' => 'because of'],
                'correct_answer' => 'A',
                'explanation' => '"despite" doğru cevaptır çünkü sınırlı teknolojiye rağmen hassasiyetin şaşırtıcı olduğunu belirtir.'
            ],
            [
                'question_text' => 'The Amazon rainforest plays a pivotal role in regulating the Earth\'s climate by absorbing vast amounts of carbon dioxide; ____ its large-scale deforestation could accelerate global warming and destabilize regional weather systems.',
                'options' => ['A' => 'despite', 'B' => 'even if', 'C' => 'as though', 'D' => 'provided that', 'E' => 'hence'],
                'correct_answer' => 'E',
                'explanation' => '"hence" doğru cevaptır çünkü ormansızlaşmanın sonucunu belirtir.'
            ],
            [
                'question_text' => 'Extensive deforestation has led to significant biodiversity loss, disrupted water cycles, and increased greenhouse gas emissions; ____, many governments continue to prioritize short-term economic gains over long-term environmental sustainability.',
                'options' => ['A' => 'nevertheless', 'B' => 'because', 'C' => 'inasmuch as', 'D' => 'as', 'E' => 'as a result'],
                'correct_answer' => 'A',
                'explanation' => '"nevertheless" doğru cevaptır çünkü çevresel zararlarına rağmen hükümetlerin davranışını belirtir.'
            ],
            [
                'question_text' => 'Nutritional experts warned that eliminating entire food groups without professional guidance might lead to deficiencies; the committee, ____ , encouraged a balanced dietary model rooted in moderation and variety.',
                'options' => ['A' => 'despite', 'B' => 'instead', 'C' => 'rather than', 'D' => 'however', 'E' => 'in fact'],
                'correct_answer' => 'B',
                'explanation' => '"instead" doğru cevaptır çünkli komitenin alternatif yaklaşımını belirtir.'
            ],
            [
                'question_text' => 'The new legislation mandates equal access to public services ____ an individual\'s ethnicity, religion, or socio-economic background, reinforcing the principle of non-discrimination embedded in international human rights conventions.',
                'options' => ['A' => 'as though', 'B' => 'in case of', 'C' => 'irrespective of', 'D' => 'provided that', 'E' => 'owing to'],
                'correct_answer' => 'C',
                'explanation' => '"irrespective of" doğru cevaptır çünkü etnik köken, din vb. fark etmeksizin eşit erişimi belirtir.'
            ],
            [
                'question_text' => 'Numerous celebrities frequently endorse unrealistic beauty standards on social media platforms; ____ , a growing number of adolescents report decreased self-esteem and body dissatisfaction.',
                'options' => ['A' => 'provided that', 'B' => 'in contrast', 'C' => 'even so', 'D' => 'consequently', 'E' => 'as if'],
                'correct_answer' => 'D',
                'explanation' => '"consequently" doğru cevaptır çünkü ünlülerin davranışının sonucunu belirtir.'
            ],
            [
                'question_text' => 'Several Scandinavian countries have implemented progressive parental leave policies to promote gender equality in childcare responsibilities; ____ , educational campaigns have been launched to challenge traditional gender roles from an early age.',
                'options' => ['A' => 'instead', 'B' => 'likewise', 'C' => 'lest', 'D' => 'whereas', 'E' => 'however'],
                'correct_answer' => 'B',
                'explanation' => '"likewise" doğru cevaptır çünkü benzer şekilde eğitim kampanyalarının da başlatıldığını belirtir.'
            ],
            [
                'question_text' => 'The committee acknowledged the urgency of implementing stricter regulations to prevent future conflicts; ____ , some members expressed concern about limiting regional autonomy and upsetting delicate political balances.',
                'options' => ['A' => 'therefore', 'B' => 'even though', 'C' => 'on the other hand', 'D' => 'due to', 'E' => 'as a result'],
                'correct_answer' => 'C',
                'explanation' => '"on the other hand" doğru cevaptır çünkü komitenin farklı görüşlerini belirtir.'
            ],
            [
                'question_text' => 'The recent study highlights the growing unpredictability of global weather systems, ____ seasonal patterns no longer follow their historical trends and extreme phenomena such as heatwaves and flash floods are occurring with greater frequency.',
                'options' => ['A' => 'in that', 'B' => 'even if', 'C' => 'unless', 'D' => 'rather than', 'E' => 'instead of'],
                'correct_answer' => 'A',
                'explanation' => '"in that" doğru cevaptır çünkü öngörülemezliğin açıklamasını verir.'
            ],
            [
                'question_text' => 'Scientists in the Arctic have been closely monitoring the rapid melting of sea ice, which threatens native wildlife and alters ocean currents; ____ , researchers in the Antarctic are investigating the stability of massive ice shelves that could contribute significantly to global sea level rise.',
                'options' => ['A' => 'consequently', 'B' => 'otherwise', 'C' => 'though', 'D' => 'in case', 'E' => 'meanwhile'],
                'correct_answer' => 'E',
                'explanation' => '"meanwhile" doğru cevaptır çünkü aynı zamanda Antarktika\'daki araştırmaları belirtir.'
            ],
            [
                'question_text' => 'Terrestrial animals have developed a wide range of physiological adaptations to survive in arid and variable climates — such as water retention mechanisms, specialized limb structures, and heat regulation systems; ____ , some species exhibit complex behavioral strategies like nocturnal activity or seasonal migration to cope with environmental stressors.',
                'options' => ['A' => 'although', 'B' => 'unless', 'C' => 'on the contrary', 'D' => 'regardless of', 'E' => 'in addition'],
                'correct_answer' => 'E',
                'explanation' => '"in addition" doğru cevaptır çünkü fizyolojik adaptasyonlara ek olarak davranışsal stratejileri belirtir.'
            ],
            [
                'question_text' => 'Concerns about digital privacy violations are increasing rapidly; ____ , corporations persist in collecting vast amounts of user data for targeted advertising.',
                'options' => ['A' => 'because', 'B' => 'notwithstanding', 'C' => 'unless', 'D' => 'similarly', 'E' => 'as though'],
                'correct_answer' => 'B',
                'explanation' => '"notwithstanding" doğru cevaptır çünkü gizlilik endişelerine rağmen şirketlerin davranışını belirtir.'
            ]
        ];

        // Test 3 Sorularını Oluştur (Belgeden alınan ikinci 12 soru)
        $test3Questions = [
            [
                'question_text' => 'A few economists have proposed alternative models to minimize the risks associated with volatile markets; ____ their suggestions have gained limited traction due to institutional resistance and a lack of political will.',
                'options' => ['A' => 'hence', 'B' => 'yet', 'C' => 'as a result', 'D' => 'in that', 'E' => 'unless'],
                'correct_answer' => 'B',
                'explanation' => '"yet" doğru cevaptır çünkü önerilere rağmen sınırlı ilgi gördüğünü belirtir.'
            ],
            [
                'question_text' => 'Recent studies have shown that the human brain continues to form new neural connections throughout adulthood; ____ processing complex information, it also regulates emotions, maintains balance, and controls involuntary functions such as breathing.',
                'options' => ['A' => 'apart from', 'B' => 'because of', 'C' => 'moreover', 'D' => 'in terms of', 'E' => 'regardless of'],
                'correct_answer' => 'A',
                'explanation' => '"apart from" doğru cevaptır çünkü beynin karmaşık bilgi işlemenin yanı sıra diğer işlevlerini belirtir.'
            ],
            [
                'question_text' => 'Some desert plants have developed remarkable survival strategies; ____ , certain species can remain dormant for months until rainfall triggers their growth cycle.',
                'options' => ['A' => 'instead', 'B' => 'as a result', 'C' => 'nevertheless', 'D' => 'even if', 'E' => 'to illustrate'],
                'correct_answer' => 'E',
                'explanation' => '"to illustrate" doğru cevaptır çünkü hayatta kalma stratejilerine örnek verir.'
            ],
            [
                'question_text' => '____ verbal abuse can leave deep emotional scars on adolescents, physical bullying often results in long-term psychological trauma that may affect their social development.',
                'options' => ['A' => 'Unless', 'B' => 'Just as', 'C' => 'In case', 'D' => 'Even if', 'E' => 'Rather than'],
                'correct_answer' => 'B',
                'explanation' => '"Just as" doğru cevaptır çünkü sözel ve fiziksel zorbalığın benzer etkilerini karşılaştırır.'
            ],
            [
                'question_text' => 'Some companies have adopted flexible work arrangements and inclusive leadership models to boost employee satisfaction and productivity; ____ , others still operate under rigid hierarchies that discourage innovation and overlook individual contributions.',
                'options' => ['A' => 'as if', 'B' => 'owing to', 'C' => 'in that', 'D' => 'while', 'E' => 'unless'],
                'correct_answer' => 'D',
                'explanation' => '"while" doğru cevaptır çünkü farklı şirket yaklaşımlarını karşılaştırır.'
            ],
            [
                'question_text' => 'The authorities decided to redesign the entire intersection with advanced signaling systems and speed-reducing measures, ____ reckless drivers might cause further fatal collisions during peak traffic hours.',
                'options' => ['A' => 'so', 'B' => 'even if', 'C' => 'for fear that', 'D' => 'as long as', 'E' => 'now that'],
                'correct_answer' => 'C',
                'explanation' => '"for fear that" doğru cevaptır çünkü dikkatsiz sürücülerin neden olabileceği kazalardan çekinildiğini belirtir.'
            ],
            [
                'question_text' => 'A significant amount of sensitive data had already been accessed by unauthorized users ____ the IT department identified the breach in the system, causing irreversible damage to the company\'s digital infrastructure.',
                'options' => ['A' => 'unless', 'B' => 'as soon as', 'C' => 'by the time', 'D' => 'even though', 'E' => 'in case'],
                'correct_answer' => 'C',
                'explanation' => '"by the time" doğru cevaptır çünkü IT departmanı ihlali tespit ettiğinde çoktan veri erişilmiş olduğunu belirtir.'
            ],
            [
                'question_text' => 'Patients diagnosed with early-stage hypertension are strongly advised to follow medical guidance and reduce their sodium intake; ____ , their condition may worsen and lead to irreversible damage to arteries and increased risk of heart attack.',
                'options' => ['A' => 'otherwise', 'B' => 'even so', 'C' => 'in fact', 'D' => 'provided that', 'E' => 'in spite of'],
                'correct_answer' => 'A',
                'explanation' => '"otherwise" doğru cevaptır çünkü tıbbi tavsiyelere uyulmaması durumunda oluşacak sonuçları belirtir.'
            ],
            [
                'question_text' => 'The article outlines the historical background of climate policy, evaluates recent legislative efforts across various countries, and discusses the long-term implications of international inaction on global warming; ____ , it provides a comprehensive overview of the challenges and opportunities in environmental governance.',
                'options' => ['A' => 'in contrast', 'B' => 'in brief', 'C' => 'for example', 'D' => 'on the other hand', 'E' => 'even though'],
                'correct_answer' => 'B',
                'explanation' => '"in brief" doğru cevaptır çünkü makalenin genel içeriğini özetler.'
            ],
            [
                'question_text' => 'The prototype aircraft completed all stages of testing successfully, including altitude endurance, engine responsiveness, and emergency landing simulations; ____ a minor issue in the navigation software, the engineers reported no significant flaws in its performance.',
                'options' => ['A' => 'in spite of', 'B' => 'regardless of', 'C' => 'in case of', 'D' => 'contrary to', 'E' => 'except for'],
                'correct_answer' => 'E',
                'explanation' => '"except for" doğru cevaptır çünkü navigasyon yazılımındaki küçük sorun dışında başka önemli sorun olmadığını belirtir.'
            ],
            [
                'question_text' => 'Several ancient kingdoms demonstrated extraordinary administrative sophistication through codified laws, centralized taxation systems, and architectural feats; ____ , the Hittites, Sumerians, and Babylonians all established bureaucratic models that influenced later empires.',
                'options' => ['A' => 'however', 'B' => 'thus', 'C' => 'instead', 'D' => 'namely', 'E' => 'in contrast'],
                'correct_answer' => 'D',
                'explanation' => '"namely" doğru cevaptır çünkü antik krallıklara örnekler verir.'
            ],
            [
                'question_text' => 'Certain insect species exhibit surprisingly complex social behavior, including cooperative brood care, division of labor, and sophisticated communication systems; ____ , ants and termites are often considered model organisms for studying eusociality.',
                'options' => ['A' => 'nevertheless', 'B' => 'indeed', 'C' => 'on the contrary', 'D' => 'otherwise', 'E' => 'even though'],
                'correct_answer' => 'B',
                'explanation' => '"indeed" doğru cevaptır çünkü karıncalar ve termitlerin gerçekten de model organizmalar olarak kabul edildiğini vurgular.'
            ]
        ];

        // Soruları veritabanına ekle
        $testIds = [
            Test::where('slug', 'ydt-deneme-1')->first()->id,
            Test::where('slug', 'yokdil-deneme-1')->first()->id,
            Test::where('slug', 'bagac-sorulari-1')->first()->id
        ];

        $allQuestions = [$test1Questions, $test2Questions, $test3Questions];

        foreach ($allQuestions as $testIndex => $questions) {
            foreach ($questions as $order => $questionData) {
                $question = Question::create([
                    'question_text' => $questionData['question_text'],
                    'question_type' => 'multiple_choice',
                    'options' => $questionData['options'],
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'],
                    'difficulty_level' => 'Orta',
                    'points' => 1,
                    'is_active' => true
                ]);

                // Soruyu teste ekle
                $question->tests()->attach($testIds[$testIndex], [
                    'order_number' => $order + 1
                ]);

                // Soruyu kategorilere ekle
                if ($testIndex === 0) {
                    $question->categories()->attach([$ydtCategory->id, $zamanlarnCategory->id]);
                } elseif ($testIndex === 1) {
                    $question->categories()->attach([$yokdilCategory->id, $zamanlarnCategory->id]);
                } else {
                    $question->categories()->attach([$ydtCategory->id, $yokdilCategory->id, $zamanlarnCategory->id]);
                }
            }
        }

        // Test-kategori ilişkilerini ekle
        $test1 = Test::where('slug', 'ydt-deneme-1')->first();
        $test2 = Test::where('slug', 'yokdil-deneme-1')->first();
        $test3 = Test::where('slug', 'bagac-sorulari-1')->first();

        $test1->categories()->attach([$ydtCategory->id, $zamanlarnCategory->id]);
        $test2->categories()->attach([$yokdilCategory->id, $zamanlarnCategory->id]);
        $test3->categories()->attach([$ydtCategory->id, $yokdilCategory->id, $zamanlarnCategory->id]);
    }

    public function down()
    {
        // Verileri temizle
        \DB::table('question_test_categories')->delete();
        \DB::table('question_tests')->delete();
        \DB::table('test_category_tests')->delete();
        \DB::table('questions')->delete();
        \DB::table('tests')->delete();
        \DB::table('test_categories')->delete();
    }
};