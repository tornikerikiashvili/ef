<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesFromDocSeeder extends Seeder
{
    public function run(): void
    {
        $services = $this->getServicesData();

        foreach ($services as $data) {
            $service = new Service;
            foreach (['en', 'ka'] as $locale) {
                $service->setTranslation('title', $locale, $data[$locale]['title']);
                $service->setTranslation('short_teaser', $locale, $data[$locale]['short_teaser']);
                $service->setTranslation('text_content', $locale, $data[$locale]['text_content']);
            }
            $service->save();
        }
    }

    /**
     * @return array<int, array{en: array{title: string, short_teaser: string, text_content: string}, ka: array{title: string, short_teaser: string, text_content: string}}>
     */
    private function getServicesData(): array
    {
        return [
            [
                'en' => [
                    'title' => 'Fit-out works',
                    'short_teaser' => 'Element FIT-OUT provides a full cycle of fit-out services, including design and interior architecture.',
                    'text_content' => "The company's experience is clearly reflected in projects delivered for international clients - from financial institutions to global commercial brands. Working across spaces of varying functions and scales has enabled Element FIT-OUT to develop a standardized yet project-specific approach tailored to each client's needs.\n\nThe service includes comprehensive interior completion - furniture selection, lighting design, planning of decorative elements, and the harmonious integration of functional details. Every solution is aimed at enhancing comfort, visual identity, and the overall user experience.\n\n" . '"Vision in Action" comes to life in every project, where an idea is transformed into a functional and aesthetically refined space.',
                ],
                'ka' => [
                    'title' => 'სარემონტო სამუშაოები',
                    'short_teaser' => '„ელემენტ ფით-აუთის“ სერვისი მოიცავს სარემონტო სამუშაოების სრულ ციკლს, პროექტირებისა და ინტერიერის დიზაინის ჩათვლით.',
                    'text_content' => 'კომპანიის გამოცდილება მკაფიოდ აისახება საერთაშორისო დამკვეთებისთვის განხორციელებულ პროექტებში - როგორც საფინანსო ინსტიტუტებისთვის, ისე გლობალური კომერციული ქსელებისთვის. განსხვავებული ფუნქციისა და მასშტაბის სივრცეებზე მუშაობამ „ელემენტ ფით-აუთს“ საშუალება მისცა ჩამოეყალიბებინა სტანდარტიზებული, თუმცა თითოეულ პროექტზე ინდივიდუალურად მორგებული სამუშაო მიდგომა.' . "\n\n" . 'სერვისი მოიცავს ინტერიერის სრულყოფილ მოწყობას - ავეჯის შერჩევას, განათების დიზაინს, დეკორატიული ელემენტების დაგეგმვასა და ფუნქციური დეტალების ჰარმონიულ შერწყმას. თითოეული გადაწყვეტა მიმართულია სივრცის კომფორტის, ვიზუალური იდენტობის და მომხმარებლის გამოცდილების გაუმჯობესებისკენ.' . "\n\n" . '„ხედვა მოქმედებაში“ - კომპანიის განაცხადი თითოეულ პროექტში რეალურ ფორმას იძენს - იდეა გარდაიქმნება ფუნქციურ და ესთეტიკურად გამართულ სივრცედ.',
                ],
            ],
            [
                'en' => [
                    'title' => 'Design',
                    'short_teaser' => 'Element FIT-OUT provides a full-cycle design service - from initial concept to final realization.',
                    'text_content' => "The result is the creation of functional, aesthetically refined, and technically sound spaces that fully comply with international standards and the client's operational requirements.\n\nThe design process includes the development of architectural and interior concepts, functional space planning and zoning, preparation of working drawings and technical documentation, integration planning of engineering systems, as well as the selection of materials and technical solutions, ensuring cost-efficiency and optimization.\n\nThe company places special emphasis on the harmonious synchronization of conceptual vision and technical solutions. This approach ensures efficient construction management, adherence to timelines, and the rational use of resources - ultimately delivering high-quality, long-lasting results.",
                ],
                'ka' => [
                    'title' => 'პროექტირება',
                    'short_teaser' => '„ელემენტ ფით-აუთი“ უზრუნველყოფს პროექტირების სრულ ციკლს - იდეიდან რეალიზებამდე.',
                    'text_content' => 'შედეგად იქმნება ფუნქციური, ესთეტიკურად გამართული და ტექნიკურად სრულყოფილი სივრცეები, რომლებიც სრულ შესაბამისობაშია საერთაშორისო სტანდარტებთან და დამკვეთის ოპერაციულ საჭიროებებთან.' . "\n\n" . 'პროექტირების პროცესი მოიცავს არქიტექტურული და ინტერიერის კონცეფციის შექმნას, სივრცის ფუნქციურ დაგეგმარებასა და ზონირებას, სამუშაო ნახაზებისა და ტექნიკური დოკუმენტაციის მომზადებას, საინჟინრო სისტემების ინტეგრაციის დაგეგმვას, ასევე მასალებისა და ტექნიკური გადაწყვეტების შერჩევას, ხარჯთეფექტურობისა და ოპტიმიზაციის უზრუნველყოფით.' . "\n\n" . 'კომპანია განსაკუთრებულ ყურადღებას უთმობს კონცეპტუალური ხედვისა და ტექნიკური გადაწყვეტების ჰარმონიულ სინქრონიზაციას. სწორედ ეს მიდგომა უზრუნველყოფს სამშენებლო პროცესის ეფექტურ მართვას, ვადების დაცვას და რესურსების გონივრულ გამოყენებას, საბოლოოდ კი - ხარისხიან და გრძელვადიან შედეგს.',
                ],
            ],
            [
                'en' => [
                    'title' => 'Interior Design',
                    'short_teaser' => 'Interior design begins with understanding the character of a space and its intended purpose.',
                    'text_content' => "Element FIT-OUT develops concepts that combine contemporary aesthetics, brand identity, and practical solutions.\n\nSpecial attention is given to material selection, color harmony, lighting and furniture design, as well as the integration of decorative details. Each element is considered part of a unified concept, ensuring that the space maintains a coherent and consistent visual identity.\n\nThe company's experience includes interior design for a wide range of environments - hotels, offices, restaurants, private residences, and more.",
                ],
                'ka' => [
                    'title' => 'ინტერიერის დიზაინი',
                    'short_teaser' => 'ინტერიერის დიზაინი იწყება სივრცის ხასიათისა და მისი დანიშნულების გააზრებით.',
                    'text_content' => 'ელემენტ ფით-აუთი ქმნის კონცეფციებს, რომლებიც აერთიანებს თანამედროვე ესთეტიკას, ბრენდის იდენტობას და პრაქტიკულ გადაწყვეტებს.' . "\n\n" . 'სამუშაო პროცესში განსაკუთრებული ყურადღება ეთმობა მასალების შერჩევას, ფერთა ჰარმონიას, განათებისა და ავეჯის დიზაინს, ასევე დეკორატიული დეტალების ინტეგრაციას. თითოეული ელემენტი განიხილება საერთო იდეის ნაწილად, რათა სივრცემ ერთიანი და თანმიმდევრული ვიზუალური სახე შეინარჩუნოს.' . "\n\n" . 'კომპანიის გამოცდილება მოიცავს სხვადასხვა ტიპის სივრცეების ინტერიერის დიზაინს - სასტუმროების, ოფისების, რესტორნების, ინდივიდუალური საცხოვრებელი სახლების და ა.შ.',
                ],
            ],
        ];
    }
}
