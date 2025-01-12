<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        
        $teachers = [
            [
                'nip' => '198311000000000000',
                'name' => 'Agung Efianto',
                'email' => 'efianto_banjar@yahoo.com',
                'phone' => '085228456315',
            ],
            [
                'nip' => '199405000000000000',
                'name' => 'ALFI NUR HAZIZAH',
                'email' => 'alfi.azizah05@gmail.com',
                'phone' => '085799841061',
            ],
            [
                'nip' => '199108000000000000',
                'name' => 'Anggun Budiyawan',
                'email' => 'anggunbudiyawan@gmail.com',
                'phone' => '081392878256',
            ],
            [
                'nip' => '475077000000000000',
                'name' => 'Apri Linda Pratiwi',
                'email' => 'lindaptw18@gmail.com',
                'phone' => '083107186969',
            ],
            [
                'nip' => '199604000000000000',
                'name' => 'APRILIAN EPTI WAHYUNI',
                'email' => 'aprilianeptiw@gmail.com',
                'phone' => '085747704808',
            ],
            [
                'nip' => '198303000000000000',
                'name' => 'Aris Rusman',
                'email' => 'aris.arisrusman.rusman7@gmail.com',
                'phone' => '081215784502',
            ],
            [
                'nip' => '197907000000000000',
                'name' => 'Atun Istianah',
                'email' => 'iisazkaahdannisa@gmail.com',
                'phone' => '08121576012',
            ],
            [
                'nip' => '198404000000000000',
                'name' => 'Catur Budi Satrio',
                'email' => 'caturagha@gmail.com',
                'phone' => '087837892488',
            ],
            [
                'nip' => '198612000000000000',
                'name' => 'Destriana Irmayasari',
                'email' => 'irmayasari.destriana@yahoo.com',
                'phone' => '085606049434',
            ],
            [
                'nip' => '199110000000000000',
                'name' => 'Dian Fidya Hastuti',
                'email' => 'dianfidyahastuti.03@gmail.com',
                'phone' => '0895377025673',
            ],
            [
                'nip' => '198210000000000000',
                'name' => 'Didiet Wisang Wibowo',
                'email' => 'didietalfa@gmail.com',
                'phone' => '088221687753',
            ],
            [
                'nip' => '198712000000000000',
                'name' => 'Eko Budi Santoso',
                'email' => 'kdkhimura@gmail.com',
                'phone' => '085729050161',
            ],
            [
                'nip' => '199604000000000001',
                'name' => 'EKO SANTOSO',
                'email' => 'Santoso456@gmail.com',
                'phone' => '082247343960',
            ],
            [
                'nip' => '199207000000000000',
                'name' => 'ENDAH YULIANI',
                'email' => 'endahyuliani13@gmail.com',
                'phone' => '081226322095',
            ],
            [
                'nip' => '244776000000000000',
                'name' => 'Evy Indah Nurmaningsih',
                'email' => 'evyindah1511@gmail.com',
                'phone' => '082221555536',
            ],
            [
                'nip' => '199404000000000000',
                'name' => 'FAERA ASTUTI',
                'email' => 'faeraastuti46@gmail.com',
                'phone' => '08112917432',
            ],
            [
                'nip' => '934477000000000000',
                'name' => 'Farah Kun Arifah',
                'email' => 'fara.queinz@gmail.com',
                'phone' => '085747204046',
            ],
            [
                'nip' => '198102000000000000',
                'name' => 'Farkhah Tri Aryani',
                'email' => 'farkhahtriaryani@gmail.com',
                'phone' => '081328825395',
            ],
            [
                'nip' => '197710000000000000',
                'name' => 'Fifin Oktarini',
                'email' => 'fifinokta24@gmail.com',
                'phone' => '081327727751',
            ],
            [
                'nip' => '197912000000000000',
                'name' => 'Gunawan Wibisono',
                'email' => 'gunawan@kkpi.or.id',
                'phone' => '081327057283',
            ],
            [
                'nip' => '733577000000000000',
                'name' => 'Guntur Yugo Pratomo',
                'email' => 'gunturyugo12@gmail.com',
                'phone' => '085865782782',
            ],
            [
                'nip' => '197405000000000000',
                'name' => 'Harni Nuril Fitri',
                'email' => 'fitriharni151@gmail.com',
                'phone' => '081337267220',
            ],
            [
                'nip' => '197710000000000001',
                'name' => 'Hasrining Wulandari',
                'email' => 'hasrining2015@gmail.com',
                'phone' => '085869333747',
            ],
            [
                'nip' => '199405000000000001',
                'name' => 'HERMANTO',
                'email' => 'herman.ptik@gmail.com',
                'phone' => '081904795400',
            ],
            [
                'nip' => '198308000000000000',
                'name' => 'Idiarso',
                'email' => 'idiarsosimbang99@gmail.com',
                'phone' => '081327382348',
            ],
            [
                'nip' => '197006000000000000',
                'name' => 'Jarwo',
                'email' => 'jarwojrw16@gmail.com',
                'phone' => '081327044739',
            ],
            [
                'nip' => '198907000000000000',
                'name' => 'Karmiyati',
                'email' => 'myutmya@gmail.com',
                'phone' => '085290597770',
            ],
            [
                'nip' => '199505000000000000',
                'name' => 'KIAT UJI PURWANI',
                'email' => 'purwanikiat@gmail.com',
                'phone' => '083109834954',
            ],
            [
                'nip' => '198509000000000000',
                'name' => 'Kiki Rahayu',
                'email' => 'aqmarina1811@gmail.com',
                'phone' => '085291060145',
            ],
            [
                'nip' => '198908000000000000',
                'name' => 'Kuswanti',
                'email' => 'keaziyad22@gmail.com',
                'phone' => '085293969096',
            ],
            [
                'nip' => '198905000000000000',
                'name' => 'Lia Dwi Arumsari',
                'email' => 'liadwiarumsari@yahoo.co.id',
                'phone' => '085743589078',
            ],
            [
                'nip' => '197706000000000000',
                'name' => 'Lili Kusliamah',
                'email' => 'kusliamahlili@gmail.com',
                'phone' => '085226929671',
            ],
            [
                'nip' => '199303000000000000',
                'name' => 'LUKMAN BUDI ANTO',
                'email' => 'kj1.lukman@gmail.com',
                'phone' => '082227588459',
            ],
            [
                'nip' => '197205000000000000',
                'name' => 'Lusinah',
                'email' => 'loeslestiono@gmail.com',
                'phone' => '085329405364',
            ],
            [
                'nip' => '197901000000000000',
                'name' => 'Mohamad Lutfi Hani Syukri',
                'email' => 'mahillatuzzahra@gmail.com',
                'phone' => '082226740055',
            ],
            [
                'nip' => '224477000000000000',
                'name' => 'Mohamad Septulloh',
                'email' => 'm.septulloh@gmail.com',
                'phone' => '085869660760',
            ],
            [
                'nip' => '198604000000000000',
                'name' => 'Mohamad Yogi Prasetyo',
                'email' => 'prastyogie25@gmail.com',
                'phone' => '088221510799',
            ],
            [
                'nip' => '923776000000000000',
                'name' => 'Muh. Iman Satriya Gotama',
                'email' => 'satriyagotama09@gmail.com',
                'phone' => '081574469635',
            ],
            [
                'nip' => '906077000000000000',
                'name' => 'Muhammad Lathif Waskito',
                'email' => 'muhammadwaskito@yahoo.com',
                'phone' => '085647959046',
            ],
            [
                'nip' => '197509000000000000',
                'name' => 'Muji Novianto',
                'email' => 'mujinovianto14@gmail.com',
                'phone' => '085200842055',
            ],
            [
                'nip' => '0252770671130153',
                'name' => 'NAFAN SAEFULLOH',
                'email' => 'aang09941@gmail.com',
                'phone' => '08816769835',
            ],
            [
                'nip' => '196508000000000000',
                'name' => 'Ngaliman',
                'email' => 'glman0865@gmail.com',
                'phone' => '082331903244',
            ],
            [
                'nip' => '197806000000000000',
                'name' => 'Nining Yuni Prabawanti',
                'email' => 'nininganto09@gmail.com',
                'phone' => '081327440806',
            ],
            [
                'nip' => '197011000000000000',
                'name' => 'Nur Fitrijono',
                'email' => 'nurgelviano1970@gmail.com',
                'phone' => '081327909113',
            ],
            [
                'nip' => '199201000000000000',
                'name' => 'Nursetya Pamujiasih',
                'email' => 'nsp.asih@gmail.com',
                'phone' => '085771131532',
            ],
            [
                'nip' => '197307000000000000',
                'name' => 'Nurul Huda',
                'email' => 'nurulslepa@gmail.com',
                'phone' => '085291477925',
            ],
            [
                'nip' => '198301000000000000',
                'name' => 'Priyatin Sutarwanto',
                'email' => 'pswanto99@gmail.com',
                'phone' => '085227150229',
            ],
            [
                'nip' => '197406000000000000',
                'name' => 'Purwono',
                'email' => 'purwonopbg1@gmail.com',
                'phone' => '08139053185',
            ],
            [
                'nip' => '198505000000000000',
                'name' => 'Riyanto',
                'email' => 'riyantobrilian@gmail.com',
                'phone' => '085329181868',
            ],
            [
                'nip' => '845677000000000000',
                'name' => 'Rizki Kharismawati',
                'email' => 'rizkikharisma1994@gmail.com',
                'phone' => '087804120278',
            ],
            [
                'nip' => '196604000000000000',
                'name' => 'Rokhman',
                'email' => 'rokhman86@yahoo.co.id',
                'phone' => '08112701139',
            ],
            [
                'nip' => '964477000000000000',
                'name' => 'ROMADHON',
                'email' => 'romadhondaryono7@gmail.com',
                'phone' => '085747318679',
            ],
            [
                'nip' => '197706000000000001',
                'name' => 'Sapto Pujianto',
                'email' => 'saptobo2019@gmail.com',
                'phone' => '081328748104',
            ],
            [
                'nip' => '199306000000000000',
                'name' => 'Sidik Nurcahyo',
                'email' => 'sidiknurcahyo@smknegeri1punggelan.sch.id',
                'phone' => '08818788233',
            ],
            [
                'nip' => '199110000000000001',
                'name' => 'Sigit Prihantono',
                'email' => 'sigit.prihantono@gmail.com',
                'phone' => '085726297080',
            ],
            [
                'nip' => '197808000000000000',
                'name' => 'Sri Herawan Kusuma',
                'email' => 'sriherawankusuma@yahoo.co.id',
                'phone' => '08170600043',
            ],
            [
                'nip' => '197204000000000000',
                'name' => 'Suparman',
                'email' => 'suparmanuye7244@gmail.com',
                'phone' => '081327474730',
            ],
            [
                'nip' => '196810000000000000',
                'name' => 'Supriyana',
                'email' => 'supriyanabna@gmail.com',
                'phone' => '085227518270',
            ],
            [
                'nip' => '199504000000000000',
                'name' => 'Supriyani',
                'email' => 'yanisupri49@gmail.com',
                'phone' => '085726314902',
            ],
            [
                'nip' => '197410000000000000',
                'name' => 'Susmiyati',
                'email' => 'syati200@gmail.com',
                'phone' => '0895388461763',
            ],
            [
                'nip' => '198610000000000000',
                'name' => 'Syaifudin Aji Negara',
                'email' => 'ghara_7086@yahoo.co.id',
                'phone' => '087837575050',
            ],
            [
                'nip' => '445877000000000000',
                'name' => 'Teni Nur Arifin',
                'email' => 'teninurarifin@gmail.com',
                'phone' => '082225480774',
            ],
            [
                'nip' => '198811000000000000',
                'name' => 'Titis Sulistarini',
                'email' => 'titissulistarini07@gmail.com',
                'phone' => '085700263289',
            ],
            [
                'nip' => '197809000000000000',
                'name' => 'Tri Haryadi',
                'email' => 'pakharyadi3@gmail.com',
                'phone' => '081329090225',
            ],
            [
                'nip' => '199108000000000001',
                'name' => 'Tri Nur Huda',
                'email' => 'trinurhuda.pbg@gmail.com',
                'phone' => '085713550333',
            ],
            [
                'nip' => '198903000000000000',
                'name' => 'Umi Rofingah',
                'email' => 'urofingah@gmail.com',
                'phone' => '088806063144',
            ],
            [
                'nip' => '198412000000000000',
                'name' => 'Wahyono',
                'email' => 'wahyonopenjaskes@gmail.com',
                'phone' => '081575963332',
            ],
            [
                'nip' => '196905000000000000',
                'name' => 'Warni Astuti Kw',
                'email' => 'kusumawardaniwarniastuti1305@gmail.com',
                'phone' => '081327107469',
            ],
            [
                'nip' => '198006000000000000',
                'name' => 'Wijayadi Muliawan',
                'email' => 'wijayadi1106@gmail.com',
                'phone' => '085643173016',
            ],
            [
                'nip' => '198911000000000000',
                'name' => 'Yan Indra Pratama',
                'email' => 'yanindrapratama@gmail.com',
                'phone' => '085739817100',
            ],
            [
                'nip' => '197108000000000000',
                'name' => 'Yudi Hermawan',
                'email' => 'youdhi2009@yahoo.co.id',
                'phone' => '085763542813',
            ],
            [
                'nip' => '198601000000000000',
                'name' => 'Yusuf Abdillah',
                'email' => 'yusup468@gmail.com',
                'phone' => '085641580044',
            ],
        ];

        foreach ($teachers as $teacher) {
            $user = User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('password'),
                'nip' => $teacher['nip'],
                'phone' => $teacher['phone'],
                'email_verified_at' => now(),
            ]);

            $user->assignRole('guru');
        }
    }
} 