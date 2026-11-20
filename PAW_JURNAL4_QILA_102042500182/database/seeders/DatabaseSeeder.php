<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // 6. Akun Mahasiswa Tambahan
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Aisyah',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        // 7. Mata Kuliah Tambahan Pertama
        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF302'],
            [
                'nama_mk' => 'Basis Data',
                'deskripsi' => 'Mempelajari konsep basis data, ERD, SQL, normalisasi, dan pengelolaan database.',
                'sks' => 3,
                'semester' => 'Genap 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 8. Mata Kuliah Tambahan Kedua
        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF303'],
            [
                'nama_mk' => 'Analisis dan Perancangan Sistem Informasi',
                'deskripsi' => 'Mempelajari analisis kebutuhan, pemodelan proses bisnis, UML, dan perancangan sistem informasi.',
                'sks' => 3,
                'semester' => 'Ganjil 2027/2028',
                'dosen_id' => $dosen->id,
            ]
        );

        // 9. Daftarkan Mahasiswa ke Mata Kuliah
        $course2->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        $course3->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
            $mahasiswa2->id => ['status' => 'approved'],
        ]);

        // 10. Tambahkan Materi Mata Kuliah
        Material::updateOrCreate(
            ['course_id' => $course2->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Basis Data',
                'konten' => 'Materi mengenai konsep dasar basis data, tabel, record, field, dan relasi antar tabel.',
            ]
        );

        Material::updateOrCreate(
            ['course_id' => $course3->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Analisis Sistem',
                'konten' => 'Materi mengenai analisis kebutuhan sistem dan pemodelan proses bisnis.',
            ]
        );

        // 11. Tambahkan Tugas Pertama
        $assignment1 = Assignment::updateOrCreate(
            [
                'course_id' => $course1->id,
                'judul' => 'Membuat Aplikasi CRUD Laravel',
            ],
            [
                'deskripsi' => 'Buat aplikasi CRUD sederhana menggunakan Laravel dengan konsep MVC dan Eloquent ORM.',
                'deadline' => now()->addDays(7),
            ]
        );

        // 12. Tambahkan Tugas Kedua
        $assignment2 = Assignment::updateOrCreate(
            [
                'course_id' => $course2->id,
                'judul' => 'Membuat ERD Basis Data',
            ],
            [
                'deskripsi' => 'Buat ERD berdasarkan studi kasus sistem informasi akademik.',
                'deadline' => now()->addDays(14),
            ]
        );

        // 13. Contoh Data Submission
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment1->id,
                'student_id' => $mahasiswa1->id,
            ],
            [
                'file_path' => 'submissions/contoh_crud_laravel.pdf',
                'catatan' => 'Berikut hasil pengerjaan tugas CRUD Laravel.',
                'status' => 'submitted',
            ]
        );

        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment2->id,
                'student_id' => $mahasiswa2->id,
            ],
            [
                'file_path' => 'submissions/contoh_erd_basis_data.pdf',
                'catatan' => 'Berikut hasil pengerjaan ERD Basis Data.',
                'status' => 'submitted',
            ]
        );
    }
}
