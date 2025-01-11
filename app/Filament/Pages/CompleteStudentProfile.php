<?php

namespace App\Filament\Pages;

use App\Models\StudentDetail;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class CompleteStudentProfile extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Lengkapi Profil';
    protected static ?string $title = 'Lengkapi Data Diri';
    protected static ?string $slug = 'complete-profile';
    protected static ?string $navigationGroup = 'Akun';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.complete-student-profile';

    public ?array $data = [];

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('student'), 403);

        $studentDetail = StudentDetail::firstOrCreate(
            ['user_id' => auth()->id()],
            []
        );

        $this->form->fill($studentDetail->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pribadi')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('nipd')
                                    ->label('NIPD')
                                    ->required(),
                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->required(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('birth_place')
                                    ->label('Tempat Lahir')
                                    ->required(),
                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->required(),
                                TextInput::make('nik')
                                    ->label('NIK')
                                    ->required()
                                    ->length(16),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('religion')
                                    ->label('Agama')
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                    ])
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('Telepon')
                                    ->tel(),
                                TextInput::make('mobile_phone')
                                    ->label('HP')
                                    ->required()
                                    ->tel(),
                            ]),
                    ]),

                Section::make('Alamat')
                    ->schema([
                        TextInput::make('address')
                            ->label('Alamat')
                            ->required(),
                        Grid::make(4)
                            ->schema([
                                TextInput::make('rt')
                                    ->label('RT')
                                    ->required()
                                    ->length(3),
                                TextInput::make('rw')
                                    ->label('RW')
                                    ->required()
                                    ->length(3),
                                TextInput::make('dusun')
                                    ->label('Dusun'),
                                TextInput::make('kelurahan')
                                    ->label('Kelurahan/Desa')
                                    ->required(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required(),
                                TextInput::make('postal_code')
                                    ->label('Kode Pos')
                                    ->required()
                                    ->length(5),
                                TextInput::make('distance_to_school')
                                    ->label('Jarak ke Sekolah (KM)')
                                    ->numeric()
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('residence_type')
                                    ->label('Jenis Tinggal')
                                    ->options([
                                        'Rumah Sendiri' => 'Rumah Sendiri',
                                        'Rumah Orang Tua' => 'Rumah Orang Tua',
                                        'Rumah Saudara/Kerabat' => 'Rumah Saudara/Kerabat',
                                        'Rumah Sewa/Kontrak' => 'Rumah Sewa/Kontrak',
                                        'Asrama' => 'Asrama',
                                        'Panti Asuhan' => 'Panti Asuhan',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required(),
                                Select::make('transportation')
                                    ->label('Alat Transportasi')
                                    ->options([
                                        'Jalan Kaki' => 'Jalan Kaki',
                                        'Sepeda' => 'Sepeda',
                                        'Sepeda Motor' => 'Sepeda Motor',
                                        'Mobil Pribadi' => 'Mobil Pribadi',
                                        'Antar Jemput' => 'Antar Jemput',
                                        'Angkutan Umum' => 'Angkutan Umum',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Data Akademik')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('skhun')
                                    ->label('SKHUN'),
                                TextInput::make('previous_school')
                                    ->label('Sekolah Asal')
                                    ->required(),
                                TextInput::make('ijazah_number')
                                    ->label('No. Ijazah'),
                            ]),
                    ]),

                Section::make('Data Ayah')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('father_name')
                                    ->label('Nama Ayah')
                                    ->required(),
                                TextInput::make('father_nik')
                                    ->label('NIK Ayah')
                                    ->required()
                                    ->length(16),
                                TextInput::make('father_birth_year')
                                    ->label('Tahun Lahir')
                                    ->required()
                                    ->numeric()
                                    ->length(4),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('father_education')
                                    ->label('Pendidikan')
                                    ->options([
                                        'SD/Sederajat' => 'SD/Sederajat',
                                        'SMP/Sederajat' => 'SMP/Sederajat',
                                        'SMA/Sederajat' => 'SMA/Sederajat',
                                        'D1' => 'D1',
                                        'D2' => 'D2',
                                        'D3' => 'D3',
                                        'D4/S1' => 'D4/S1',
                                        'S2' => 'S2',
                                        'S3' => 'S3',
                                        'Tidak Sekolah' => 'Tidak Sekolah',
                                    ])
                                    ->required(),
                                Select::make('father_occupation')
                                    ->label('Pekerjaan')
                                    ->options([
                                        'Tidak Bekerja' => 'Tidak Bekerja',
                                        'Nelayan' => 'Nelayan',
                                        'Petani' => 'Petani',
                                        'Peternak' => 'Peternak',
                                        'PNS/TNI/Polri' => 'PNS/TNI/Polri',
                                        'Karyawan Swasta' => 'Karyawan Swasta',
                                        'Pedagang Kecil' => 'Pedagang Kecil',
                                        'Pedagang Besar' => 'Pedagang Besar',
                                        'Wiraswasta' => 'Wiraswasta',
                                        'Wirausaha' => 'Wirausaha',
                                        'Buruh' => 'Buruh',
                                        'Pensiunan' => 'Pensiunan',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required(),
                                Select::make('father_income')
                                    ->label('Penghasilan')
                                    ->options([
                                        'Kurang dari 500.000' => 'Kurang dari 500.000',
                                        '500.000 - 999.999' => '500.000 - 999.999',
                                        '1.000.000 - 1.999.999' => '1.000.000 - 1.999.999',
                                        '2.000.000 - 4.999.999' => '2.000.000 - 4.999.999',
                                        '5.000.000 - 20.000.000' => '5.000.000 - 20.000.000',
                                        'Lebih dari 20.000.000' => 'Lebih dari 20.000.000',
                                        'Tidak Berpenghasilan' => 'Tidak Berpenghasilan',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Data Ibu')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('mother_name')
                                    ->label('Nama Ibu')
                                    ->required(),
                                TextInput::make('mother_nik')
                                    ->label('NIK Ibu')
                                    ->required()
                                    ->length(16),
                                TextInput::make('mother_birth_year')
                                    ->label('Tahun Lahir')
                                    ->required()
                                    ->numeric()
                                    ->length(4),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('mother_education')
                                    ->label('Pendidikan')
                                    ->options([
                                        'SD/Sederajat' => 'SD/Sederajat',
                                        'SMP/Sederajat' => 'SMP/Sederajat',
                                        'SMA/Sederajat' => 'SMA/Sederajat',
                                        'D1' => 'D1',
                                        'D2' => 'D2',
                                        'D3' => 'D3',
                                        'D4/S1' => 'D4/S1',
                                        'S2' => 'S2',
                                        'S3' => 'S3',
                                        'Tidak Sekolah' => 'Tidak Sekolah',
                                    ])
                                    ->required(),
                                Select::make('mother_occupation')
                                    ->label('Pekerjaan')
                                    ->options([
                                        'Tidak Bekerja' => 'Tidak Bekerja',
                                        'Nelayan' => 'Nelayan',
                                        'Petani' => 'Petani',
                                        'Peternak' => 'Peternak',
                                        'PNS/TNI/Polri' => 'PNS/TNI/Polri',
                                        'Karyawan Swasta' => 'Karyawan Swasta',
                                        'Pedagang Kecil' => 'Pedagang Kecil',
                                        'Pedagang Besar' => 'Pedagang Besar',
                                        'Wiraswasta' => 'Wiraswasta',
                                        'Wirausaha' => 'Wirausaha',
                                        'Buruh' => 'Buruh',
                                        'Pensiunan' => 'Pensiunan',
                                        'Lainnya' => 'Lainnya',
                                    ])
                                    ->required(),
                                Select::make('mother_income')
                                    ->label('Penghasilan')
                                    ->options([
                                        'Kurang dari 500.000' => 'Kurang dari 500.000',
                                        '500.000 - 999.999' => '500.000 - 999.999',
                                        '1.000.000 - 1.999.999' => '1.000.000 - 1.999.999',
                                        '2.000.000 - 4.999.999' => '2.000.000 - 4.999.999',
                                        '5.000.000 - 20.000.000' => '5.000.000 - 20.000.000',
                                        'Lebih dari 20.000.000' => 'Lebih dari 20.000.000',
                                        'Tidak Berpenghasilan' => 'Tidak Berpenghasilan',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Section::make('Data Wali')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('guardian_name')
                                    ->label('Nama Wali'),
                                TextInput::make('guardian_nik')
                                    ->label('NIK Wali')
                                    ->length(16),
                                TextInput::make('guardian_birth_year')
                                    ->label('Tahun Lahir')
                                    ->numeric()
                                    ->length(4),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Select::make('guardian_education')
                                    ->label('Pendidikan')
                                    ->options([
                                        'SD/Sederajat' => 'SD/Sederajat',
                                        'SMP/Sederajat' => 'SMP/Sederajat',
                                        'SMA/Sederajat' => 'SMA/Sederajat',
                                        'D1' => 'D1',
                                        'D2' => 'D2',
                                        'D3' => 'D3',
                                        'D4/S1' => 'D4/S1',
                                        'S2' => 'S2',
                                        'S3' => 'S3',
                                        'Tidak Sekolah' => 'Tidak Sekolah',
                                    ]),
                                Select::make('guardian_occupation')
                                    ->label('Pekerjaan')
                                    ->options([
                                        'Tidak Bekerja' => 'Tidak Bekerja',
                                        'Nelayan' => 'Nelayan',
                                        'Petani' => 'Petani',
                                        'Peternak' => 'Peternak',
                                        'PNS/TNI/Polri' => 'PNS/TNI/Polri',
                                        'Karyawan Swasta' => 'Karyawan Swasta',
                                        'Pedagang Kecil' => 'Pedagang Kecil',
                                        'Pedagang Besar' => 'Pedagang Besar',
                                        'Wiraswasta' => 'Wiraswasta',
                                        'Wirausaha' => 'Wirausaha',
                                        'Buruh' => 'Buruh',
                                        'Pensiunan' => 'Pensiunan',
                                        'Lainnya' => 'Lainnya',
                                    ]),
                                Select::make('guardian_income')
                                    ->label('Penghasilan')
                                    ->options([
                                        'Kurang dari 500.000' => 'Kurang dari 500.000',
                                        '500.000 - 999.999' => '500.000 - 999.999',
                                        '1.000.000 - 1.999.999' => '1.000.000 - 1.999.999',
                                        '2.000.000 - 4.999.999' => '2.000.000 - 4.999.999',
                                        '5.000.000 - 20.000.000' => '5.000.000 - 20.000.000',
                                        'Lebih dari 20.000.000' => 'Lebih dari 20.000.000',
                                        'Tidak Berpenghasilan' => 'Tidak Berpenghasilan',
                                    ]),
                            ]),
                    ]),

                Section::make('Data Tambahan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('kk_number')
                                    ->label('Nomor KK')
                                    ->required()
                                    ->length(16),
                                TextInput::make('birth_certificate_no')
                                    ->label('No. Akta Lahir')
                                    ->required(),
                                TextInput::make('child_order')
                                    ->label('Anak ke')
                                    ->required()
                                    ->numeric(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('weight')
                                    ->label('Berat Badan (kg)')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('height')
                                    ->label('Tinggi Badan (cm)')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('siblings_count')
                                    ->label('Jumlah Saudara Kandung')
                                    ->required()
                                    ->numeric(),
                            ]),
                    ]),

                Section::make('Data Bantuan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('kps_recipient')
                                    ->label('Penerima KPS')
                                    ->reactive(),
                                TextInput::make('kps_number')
                                    ->label('Nomor KPS')
                                    ->visible(fn (Get $get) => $get('kps_recipient')),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Toggle::make('kip_recipient')
                                    ->label('Penerima KIP')
                                    ->reactive(),
                                TextInput::make('kip_number')
                                    ->label('Nomor KIP')
                                    ->visible(fn (Get $get) => $get('kip_recipient')),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('bank_name')
                                    ->label('Bank'),
                                TextInput::make('bank_account_number')
                                    ->label('Nomor Rekening'),
                                TextInput::make('bank_account_holder')
                                    ->label('Rekening Atas Nama'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $studentDetail = StudentDetail::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );

        Notification::make()
            ->title('Data berhasil disimpan')
            ->success()
            ->send();
    }
} 