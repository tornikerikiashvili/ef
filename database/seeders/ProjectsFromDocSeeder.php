<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectsFromDocSeeder extends Seeder
{
    public function run(): void
    {
        $projects = $this->getProjectsData();

        foreach ($projects as $data) {
            $project = new Project;
            foreach (['en', 'ka'] as $locale) {
                $project->setTranslation('title', $locale, $data[$locale]['title']);
                $project->setTranslation('client', $locale, $data[$locale]['client']);
                $project->setTranslation('area', $locale, $data[$locale]['area']);
                $project->setTranslation('location', $locale, $data[$locale]['location']);
                $project->setTranslation('status_text', $locale, $data[$locale]['status_text']);
                $project->setTranslation('text_content', $locale, $data[$locale]['text_content']);
            }
            $project->save();
        }
    }

    /**
     * @return array<int, array{en: array{title: string, client: string, area: string, location: string, status_text: string, text_content: string}, ka: array{title: string, client: string, area: string, location: string, status_text: string, text_content: string}}>
     */
    private function getProjectsData(): array
    {
        return [
            [
                'en' => [
                    'title' => "An International Financial Institution's Office",
                    'client' => 'An International Financial Institution',
                    'area' => '2 300 sq.m.',
                    'location' => 'Tbilisi, Georgia',
                    'status_text' => 'Completed in 2024',
                    'text_content' => "Element FIT-OUT's services are fully aligned with international standards, ensuring strict quality control, systematic process management, and full compliance with global brand guidelines. As a result of this approach, the company's portfolio features both international brands and large-scale institutions that require the highest standards in space planning and execution.\n\nOne of the most significant and successful projects was the fit-out and MEP works of the Tbilisi office of an international financial institution. The 2,300 sq.m. facility was fully designed and executed in accordance with international requirements.\n\nThe large-scale project was delivered over an 8-month period and covered the complete fit-out cycle - from the integration of engineering systems to the detailed execution of interior elements. As a result, the international financial institution received a modern, comfortable, and functionally optimized workspace that meets both operational needs and corporate identity standards.",
                ],
                'ka' => [
                    'title' => 'საერთაშორისო საფინანსო ინსტიტუტის ოფისი',
                    'client' => 'საერთაშორისო საფინანსო ინსტიტუტი',
                    'area' => '2300 კვ.მ',
                    'location' => 'თბილისი, საქართველო',
                    'status_text' => 'დასრულებულია 2024 წელს',
                    'text_content' => "Element FIT-OUT-ის სერვისები სრულ თანხვედრაშია საერთაშორისო სტანდარტებთან, რაც გულისხმობს ხარისხის მკაცრ კონტროლს, პროცესების სისტემურ მართვასა და გლობალური ბრენდების გაიდლაინების დაცვას. სწორედ ამ მიდგომის შედეგად, კომპანიის პორტფოლიოში წარმოდგენილია როგორც საერთაშორისო ბრენდები, ისე მასშტაბური ინსტიტუტები, რომლებიც სივრცის დაგეგმვისა და შესრულების მაღალ სტანდარტს ითხოვენ.\n\nერთ-ერთი მნიშვნელოვანი და წარმატებული პროექტი გახდა საერთაშორისო საფინანსო ინსტიტუტის თბილისის ოფისის სარემონტო და საინჟინრო სამუშაოები. 2 300 კვ.მ. მოცულობის ობიექტი სრულად საერთაშორისო მოთხოვნების შესაბამისად დაიგეგმა და განხორციელდა.\n\nმასშტაბური პროექტი 8 თვის განმავლობაში მიმდინარეობდა. იგი მოიცავდა სრულ სარემონტო ციკლს - საინჟინრო სისტემების ინსტალაცია-ინტეგრაციიდან ინტერიერის ცალკეულ დეტალებამდე. შედეგის სახით, საერთაშორისო საფინანსო ინსტიტუტმა მიიღო თანამედროვე სტანდარტებზე დაფუძნებული, კომფორტული და ფუნქციურად ოპტიმიზებული სამუშაო გარემო, რომელიც პასუხობს როგორც ოპერაციულ საჭიროებებს, ისე კორპორატიულ იმიჯს.",
                ],
            ],
            [
                'en' => [
                    'title' => 'Borjomi Office',
                    'client' => 'LTD IDS Borjomi Georgia',
                    'area' => '48 000 sq.m.',
                    'location' => 'Village Kvibisi, Borjomi Municipality, Georgia',
                    'status_text' => 'Completed in 2025',
                    'text_content' => "Element FIT-OUT delivered the fit-out works for the new production facility and office of Georgia's first mineral water brand, Borjomi. The project represented a strategically significant development and was executed in full compliance with international standards and strict quality control requirements.\n\nThe scope of services included the integration of engineering systems as well as the installation of modern technologies. Each stage of the process was planned and implemented with high precision to ensure that the production infrastructure was fully aligned with operational needs and international regulatory standards.\n\nThe newly consolidated factory employs 600 local professionals, generating a substantial social and economic impact. The production process fully complies with national legislation and meets international and European standards.\n\nThe facility is constructed using energy-efficient materials and equipment. Every stage of production is organized to minimize environmental impact - from energy consumption optimization to advanced waste management systems.",
                ],
                'ka' => [
                    'title' => 'ბორჯომის ოფისი',
                    'client' => 'შპს IDS ბორჯომი საქართველო',
                    'area' => '48 000 კვ.მ.',
                    'location' => 'სოფელი ყვიბისი, ბორჯომის მუნიციპალიტეტი, საქართველო',
                    'status_text' => 'დასრულებული 2025 წელს',
                    'text_content' => "„ელემენტ ფით-აუთმა“ პირველი ქართული მინერალური წყლის „ბორჯომის“ ახალი საწარმოსა და ოფისის სარემონტო სამუშაოები შეასრულა. პროექტი წარმოადგენდა სტრატეგიულ მნიშვნელობის ობიექტს, რომლის განხორციელებაც სრულად საერთაშორისო სტანდარტებისა და ხარისხის მკაცრი კონტროლის შესაბამისად განხორციელდა.\n\nსერვისი მოიცავდა საინჟინრო სისტემების ინტეგრაციას და თანამედროვე ტექნოლოგიების შესაბამის ინსტალაციას. სამუშაო პროცესის თითოეული ეტაპი დაგეგმილი იყო მაღალი სიზუსტით, რათა საწარმოო ინფრასტრუქტურა სრულად შესაბამისობაში ყოფილიყო როგორც ოპერაციულ საჭიროებთან, ისე საერთაშორისო რეგულაციებთან.\n\nახალ, გაერთიანებულ ქარხანაში დასაქმებულია 600 ადგილობრივი თანამშრომელი, რაც პროექტს მნიშვნელოვან სოციალურ და ეკონომიკურ ეფექტს სძენს. საწარმოო პროცესი სრულად შეესაბამება ეროვნულ კანონმდებლობას და აკმაყოფილებს საერთაშორისო და ევროპულ სტანდარტებს.\n\nქარხანა აგებულია ენერგოეფექტურობაზე ორიენტირებული მასალებითა და მოწყობილობებით. წარმოების თითოეული ეტაპი ისეა ორგანიზებული, რომ გარემოზე ზემოქმედება მინიმუმამდე იყოს დაყვანილი - ენერგიის მოხმარების ოპტიმიზაციიდან ნარჩენების მართვის სისტემებამდე.",
                ],
            ],
            [
                'en' => [
                    'title' => 'Girteka Office',
                    'client' => 'Ibercompany Holding',
                    'area' => '1 680 sq.m.',
                    'location' => 'Tbilisi, Georgia',
                    'status_text' => 'Completed in 2024',
                    'text_content' => "Element FIT-OUT successfully delivered the fit-out works for the Tbilisi office of GIRTEKA, one of Europe's largest logistics companies.\n\nThe scope of services included complete interior fit-out and the arrangement of technical areas. Commissioned by LLC Ibercompany, the project was implemented under strict timeframes and rigorous quality control.\n\nThe office spans 1,700 sq.m., with a layout designed to ensure functionality, comfort, and a clear expression of the brand's identity within a modern work environment.\n\nThanks to the professional project management and technical expertise of the Element FIT-OUT team, the works were successfully completed within five months. The project was implemented in 2024.",
                ],
                'ka' => [
                    'title' => 'გირტეკას ოფისი',
                    'client' => 'შპს „იბერკომპანი ჰოლდინგი“',
                    'area' => '1 700 კვ.მ.',
                    'location' => 'თბილისი, საქართველო',
                    'status_text' => 'დასრულებულია 2024 წელს',
                    'text_content' => '„ელემენტ ფით-აუთმა“ უმსხვილესი ევროპული ლოგისტიკური კომპანიის „გირტეკას“ თბილისის ოფისის სარემონტო სამუშაოები შეასრულა.'."\n\n".'სერვისი მოიცავდა ინტერიერის სრულ რემონტსა და ტექნიკური სივრცეების მოწყობას. შპს „იბერკომპანი ჰოლდინგის“ დაკვეთით, პროექტი მკაცრი ვადებისა და ხარისხის კონტროლის პირობებში მიმდინარეობდა.'."\n\n".'ოფისის მასშტაბი 1 700 კვ.მ.-ს შეადგენს. სივრცის გადანაწილება უზრუნველყოფს თანამედროვე სამუშაო გარემოს ფუნქციურობას, კომფორტსა და ბრენდის იდენტობის ეფექტურ გამოხატვას.'."\n\n".'„გირტეკას“ ოფისის სარემონტო სამუშაოები "ელემენტ ფით-აუთის" გუნდის პროფესიული მენეჯმენტითა და ტექნიკური კომპეტენციით 5 თვეში წარმატებით დასრულდა. პროექტი 2024 წელს განხორციელდა.',
                ],
            ],
            [
                'en' => [
                    'title' => 'Buknari',
                    'client' => 'LLC "Lisi Development"',
                    'area' => '11 335 sq.m.',
                    'location' => 'Buknari, Autonomous Republic of Adjara, Georgia',
                    'status_text' => 'Ongoing',
                    'text_content' => "Element FIT-OUT carried out the fit-out works for the Buknari complex located on the Black Sea coast.\n\nThe 10-storey complex features a 5-star hotel and premium-class apartments. Its infrastructure includes a restaurant, a sports ground, and an indoor swimming pool, creating a fully integrated, multifunctional environment for both leisure and residential use.\n\nThe property is located in Buknari - a unique setting between the sea and the mountains, just 15 minutes from Batumi. The region's subtropical climate and the specific coastal conditions required specialized technical approaches and the careful selection of construction materials. In response to these challenges, Element FIT-OUT ensured the integration of modern technological solutions and selected durable products suited to demanding climatic conditions.\n\nAs a result, Buknari represents a new standard of aesthetics, quality, and functionality on the Black Sea coastline.",
                ],
                'ka' => [
                    'title' => 'ბუკნარი',
                    'client' => 'შპს "ლისი დეველოპმენტი"',
                    'area' => '11 335 კვ.მ.',
                    'location' => 'ბუკნარი, აჭარის ავტონომიური რესპუბლიკა, საქართველო',
                    'status_text' => 'მიმდინარე',
                    'text_content' => "„ელემენტ ფით-აუთმა“ შავი ზღვის სანაპიროზე მდებარე კომპლექსის „ბუკნარის“ სარემონტო სამუშაოები შეასრულა.\n\n10-სართულიან კომპლექსში წარმოდგენილია 5-ვარსკვლავიანი სასტუმრო და პრემიუმ კლასის აპარტამენტები. ინფრასტრუქტურა მოიცავს რესტორანს, სპორტულ მოედანსა და დახურულ აუზს, რაც სივრცეს სრულფასოვან, მრავალფუნქციურ დასასვენებელ და საცხოვრებელ გარემოდ აქცევს.\n\nობიექტი მდებარეობს ბუკნარში - უნიკალურ ლოკაციაზე, ზღვასა და მთას შორის, ბათუმიდან 15 წუთის სავალზე. რეგიონისთვის დამახასიათებელი სუბტროპიკული კლიმატი და სანაპირო ზონის სპეციფიკა პროექტის განხორციელებისას განსაკუთრებულ ტექნიკურ მიდგომებსა და სამშენებლო მასალების სწორ შერჩევას მოითხოვდა. გამოწვევის შესაბამისად, „ელემენტ ფით-აუთმა“ უზრუნველყო თანამედროვე ტექნოლოგიური გადაწყვეტილებების ინტეგრაცია და შეარჩია მდგრადი პროდუქტები რთული კლიმატური პირობებისთვის.\n\nშედეგად „ბუკნარი“ წარმოადგენს ესთეტიკის, ხარისხისა და ფუნქციონალურობის ახალ სტანდარტს შავი ზღვის სანაპიროზე.",
                ],
            ],
            [
                'en' => [
                    'title' => 'Caucasus University Campus (University and School)',
                    'client' => 'LTD Caucasus University',
                    'area' => '15 500 sq.m.',
                    'location' => 'Batumi, Adjara',
                    'status_text' => 'Ongoing',
                    'text_content' => "The Caucasus University campus project in Batumi represents a significant milestone in the development of educational infrastructure in the region. The fit-out works are being carried out by Element FIT-OUT, while construction supervision and project management are provided by Element Construction.\n\nThe total project area covers 15,500 sq.m. The facility will be equipped with modern infrastructure, including a library, laboratories, sports grounds, and a swimming pool. The project also includes the construction of a student dormitory. The Batumi campus will offer undergraduate and graduate programs, while its educational scope will be expanded with two new directions — the Caucasus Maritime School and the Culinary Academy. A training center will also operate within the university.\n\nThe project significantly enhances access to education in the region, supports professional development, and creates a learning environment aligned with international standards.",
                ],
                'ka' => [
                    'title' => 'კავკასიის უნივერსიტეტის კამპუსი (უნივერსიტეტი და სკოლა)',
                    'client' => 'შპს "კავკასიის უნივერსიტეტი"',
                    'area' => '15 500 კვ.მ.',
                    'location' => 'ბათუმი, აჭარა',
                    'status_text' => 'მიმდინარე',
                    'text_content' => "ბათუმში კავკასიის უნივერსიტეტის კამპუსის პროექტი რეგიონში საგანმანათლებლო ინფრასტრუქტურის განვითარების მნიშვნელოვან ეტაპს წარმოადგენს. ობიექტის სარემონტო სამუშაოებს ახორციელებს „ელემენტ ფით-აუთი“, ხოლო მშენებლობის ზედამხედველობასა და მართვას უზრუნველყოფს „ელემენტ ქონსთრაქშენი“. სამუშაო ფართობი 15,500 კვ.მ-ს შეადგენს. შენობა აღიჭურვება თანამედროვე ინფრასტრუქტურით, მათ შორის ბიბლიოთეკით, ლაბორატორიებით, სპორტული მოედნებითა და საცურაო აუზით. პროექტი ასევე ითვალისწინებს სტუდენტური საერთო საცხოვრებლის მშენებლობას. ბათუმის კამპუსში შესაძლებელი იქნება სწავლა საბაკალავრო და სამაგისტრო პროგრამებზე, ხოლო საგანმანათლებლო შესაძლებლობებს გააფართოებს ორი ახალი მიმართულება - კავკასიის საზღვაო სკოლა და კულინარიის აკადემია. უნივერსიტეტში ასევე იფუნქციონირებს ტრენინგ ცენტრი.\n\nპროექტი მნიშვნელოვნად აძლიერებს რეგიონში განათლების ხელმისაწვდომობას, პროფესიული განვითარებისა და საერთაშორისო სტანდარტების შესაბამისი სასწავლო გარემოს შექმნას.",
                ],
            ],
            [
                'en' => [
                    'title' => 'Caucasus University (Tbilisi)',
                    'client' => 'LTD Caucasus University',
                    'area' => '4 055 sq.m.',
                    'location' => 'Tbilisi, Georgia',
                    'status_text' => 'Ongoing',
                    'text_content' => "In parallel with the fit-out works at the Caucasus University campus in Batumi, Element FIT-OUT is also carrying out renovation works at the university's facility in Tbilisi.\n\nThe capital-based project covers a total area of 4,055 sq.m. and aims to create a modern, functional learning environment aligned with international standards.\n\nThe simultaneous implementation of works in two cities clearly demonstrates a high pace of execution, efficient project management, and the client's trust.",
                ],
                'ka' => [
                    'title' => 'კავკასიის უნივერსიტეტი (თბილისი)',
                    'client' => 'შპს "კავკასიის უნივერსიტეტი"',
                    'area' => '4 055 კვ.მ.',
                    'location' => 'თბილისი, საქართველო',
                    'status_text' => 'მიმდინარე',
                    'text_content' => "ბათუმში კავკასიის უნივერსიტეტის კამპუსის სარემონტო სამუშაოების პარალელურად, „ელემენტ ფით-აუთი“ უნივერსიტეტის თბილისში მდებარე ობიექტზეც მუშაობს.\n\nდედაქალაქში მდებარე პროექტის საერთო ფართობი 4, 055 კვ.მ.-ს შეადგენს და მიზნად ისახავს თანამედროვე, ფუნქციური და საერთაშორისო სტანდარტებთან შესაბამისი სასწავლო გარემოს შექმნას.\n\nორი ქალაქში ერთდროულად მიმდინარე სამუშაოები ნათლად ადასტურებს სამუშაო პროცესების მაღალ ტემპს, ეფექტურ მენეჯმენტსა და დამკვეთის ნდობას.",
                ],
            ],
            [
                'en' => [
                    'title' => 'Terminal Towers',
                    'client' => 'Terminal LLC',
                    'area' => '2 840 sq.m.',
                    'location' => 'Tbilisi, Georgia',
                    'status_text' => 'Completed in 2020',
                    'text_content' => "Terminal Towers is the largest co-working space in the region, with its full fit-out works completed by Element FIT-OUT.\n\nWithin the project scope, conference halls, meeting rooms, offices, a café-bar, relaxation areas, and various functional spaces were created to support a modern and flexible working environment. The project stands out for premium-segment solutions, including the development of new design elements, while every interior detail reflects the latest trends. The facility is also distinguished by the high-quality materials used during the fit-out process. Among them is terrazzo flooring, which serves as one of the interior's most striking visual features. The stage-style meeting room incorporates exclusive Rustik flooring, adding a distinct identity to the space.\n\nThe facility is designed for 500 users, which is an unprecedented scale in the region and sets a new standard for modern co-working space. Interior designer: Evgenia Gordinskaya.",
                ],
                'ka' => [
                    'title' => 'ტერმინალ თაუერსი',
                    'client' => 'შპს „ტერმინალი“',
                    'area' => '2 840 კვ.მ.',
                    'location' => 'თბილისი',
                    'status_text' => 'დასრულებულია 2020 წელს',
                    'text_content' => "„ტერმინალ თაუერსი“ რეგიონში ყველაზე დიდი საერთო სამუშაო სივრცეა, რომლის სრული სარემონტო სამუშაოები „ელემენტ ფით-აუთმა“ განახორციელა.\n\nპროექტის ფარგლებში მოეწყო საკონფერენციო დარბაზები, შეხვედრების ოთახები, ოფისები, კაფე-ბარი, სარელაქსაციო ზონა და სხვადასხვა ფუნქციური სივრცე. პროექტი გამორჩეულია პრემიუმ სეგმენტისთვის დამახასიათებელი გადაწყვეტილებებით, რაც დიზაინის მხრივ ახალი პროდუქტების შექმნასაც მოიცავდა. ინტერიერის თითოეული დეტალი პასუხობს უახლოესს ტენდენციებს. ობიექტი გამორჩეულია რემონტის პროცესში გამოყენებული მასალების თვალსაზრისითაც. მათ შორისაა, ტერაცოს იატაკი, რომელიც ინტერიერის ერთ-ერთ გამორჩეულ ვიზუალურ ელემენტად გვევლინება. შეხვედრებისთვის განკუთვნილ სცენა-ოთახში გამოყენებულია ექსკლუზიური Rustik-ის იატაკი, რაც სივრცეს დამატებით ინდივიდუალობას ანიჭებს.\n\nობიექტი გათვლილია 500 მომხმარებელზე, რაც მასშტაბით რეგიონში უპრეცედენტო მაჩვენებელია და ქმნის თანამედროვე საერთო სამუშაო სივრცის ახალ სტანდარტს. პროექტის ინტერიერის დიზაინერია ევგენია გორდინსკაია.",
                ],
            ],
        ];
    }
}
