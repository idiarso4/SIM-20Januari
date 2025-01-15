# Dokumentasi Alur Pengembangan Aplikasi Akademik

## Struktur Utama

### 1. Core Features (Fitur Inti)
- [x] Manajemen Pengguna
  - Users
  - Roles
  - Permissions
- [x] Master Data
  - Kelas
  - Mata Pelajaran
  - Tahun Ajaran
- [x] Manajemen Siswa
- [x] Manajemen Guru

### 2. Modul Pembelajaran

#### 2.1 Kegiatan Mengajar (Teaching Activities)
- [x] Pencatatan aktivitas mengajar
- [x] Absensi siswa
  - Status: Hadir, Sakit, Izin, Alpha, Dispensasi
  - Bulk action untuk absensi massal
  - Filter berdasarkan status
- [x] Export data (Excel)
- [x] Integrasi dengan jurnal

##### Integrasi dengan Jurnal
- Jurnal otomatis dibuat saat:
  - Membuat aktivitas mengajar baru
  - Mengupdate aktivitas mengajar
  - Menghapus aktivitas mengajar
- Format jurnal:
  - Jenis: mengajar
  - Deskripsi: "Mengajar {mata_pelajaran} di kelas {nama_kelas}"
  - Source: teacher-journals

##### Testing
- Create: Memastikan jurnal terbuat saat membuat aktivitas
- Update: Memastikan jurnal terupdate saat mengubah aktivitas
- Delete: Memastikan jurnal terhapus saat menghapus aktivitas

#### 2.2 Penilaian (Assessments)
- [x] Input nilai
  - Jenis: Sumatif, Non-Sumatif
  - Kategori: Teori, Praktik
- [x] Rekap nilai
- [x] Export data
- [x] Integrasi dengan jurnal

#### 2.3 Ekstrakurikuler
- [x] Pendaftaran
- [x] Absensi
- [x] Export data
- [x] Integrasi dengan jurnal

### 3. Jurnal & Laporan
- [x] Jurnal otomatis dari setiap modul
- [x] Export data terpadu
- [x] Dashboard statistik

## Standar Implementasi

### 1. Database
php
// Model harus mengikuti struktur:
class ModuleModel extends Model
{
// 1. Konstanta untuk status/opsi
public const STATUS_A = 'a';
public const STATUS_OPTIONS = [...];
public const STATUS_COLORS = [...];
// 2. Fillable & Casts
protected $fillable = [...];
protected $casts = [...];
// 3. Relationships
public function relationA(): BelongsTo {...}
public function journal(): MorphOne {...}
}

### 2. Admin Panel

php
// Resource harus mengikuti struktur:
class ModuleResource extends Resource
{
// 1. Navigation
protected static ?string $navigationGroup = 'Akademik';
protected static ?int $navigationSort = 1;
// 2. Form
public static function form(Form $form): Form {...}
// 3. Table
public static function table(Table $table): Table {...}
// 4. Relations
public static function getRelations(): array {...}
// 5. Widgets
public static function getWidgets(): array {...}
}

### 3. Export Handler
php
// Export harus mengikuti struktur:
class ModuleExport extends BaseExport
{
public function query() {...}
public function headings(): array {...}
public function map($row): array {...}
}

## Proses Pengembangan

### 1. Membuat Fitur Baru
bash
1. Buat branch
git checkout -b feature/nama-modul
2. Generate files
php artisan make:model NamaModul -mf
php artisan make:filament-resource NamaModulResource
php artisan make:export NamaModulExport
3. Testing
php artisan test --filter=NamaModulTest
1. Buat branch
git checkout -b feature/nama-modul
2. Generate files
php artisan make:model NamaModul -mf
php artisan make:filament-resource NamaModulResource
php artisan make:export NamaModulExport
3. Testing
php artisan test --filter=NamaModulTest


### 2. Checklist Implementasi
- [ ] Migration dengan relasi yang benar
- [ ] Model dengan konstanta dan relasi
- [ ] Resource dengan form dan table standar
- [ ] RelationManager jika diperlukan
- [ ] Widget statistik
- [ ] Export handler
- [ ] Jurnal integration
- [ ] Tests

## Catatan Penting
1. Selalu gunakan konstanta untuk status/opsi
2. Pastikan integrasi jurnal berfungsi
3. Selalu sertakan fitur export
4. Perhatikan permission dan role
5. Buat test untuk setiap fitur utama
EOF