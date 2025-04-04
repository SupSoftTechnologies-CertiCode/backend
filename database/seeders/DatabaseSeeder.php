<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use App\Models\Seminar;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CertificateTemplate::create([
            'name' => 'Certificate of Participation',
            'pdf_filename' => 'certificates/RKOPGnDcFQJcBtFQCcr3CghWqmFQ6uE3nl2uIFSR.pdf',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Seminar::insert([
            [
                'name_of_seminar' => 'Mastering Tailwind CSS: A Modern Approach to Styling',
                'topics' => 'Tailwind',
                'description' => 'This seminar covers the fundamentals and advanced techniques of Tailwind CSS, a utility-first CSS framework that helps developers build modern and responsive designs quickly.',
                'date' => '2025-04-01',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Jayson Delos Santos',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/KTPH9UR5SEMHYvoYWMAtg050EzCLcyGjFxHBzY7L.jpg',
                'seminar_image' => 'images/seminar_images/MEdRMJy4bn9jEyMqUlLNmnJg7WdqP52CMOgbnv2d.png',
                'about_the_speaker' => 'Jayson Delos Santos is a front-end developer with over seven years of experience specializing in CSS frameworks. He has contributed to open-source projects and is an advocate of Tailwind CSS for efficient styling.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'Git Essentials: Version Control for Developers',
                'topics' => 'Git',
                'description' => 'This seminar explores the basics of Git, including repositories, branches, commits, merges, and best practices for collaboration in software development.',
                'date' => '2025-04-02',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Lander Pelagio',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/KTPH9UR5SEMHYvoYWMAtg050EzCLcyGjFxHBzY7L.jpg',
                'seminar_image' => 'images/seminar_images/bi5UAWgvzpL17fmU7zHYSJEnURQpxlVtxIP2Dmm1.png',
                'about_the_speaker' => 'Lander Pelagio is a DevOps engineer with expertise in source code management. He has conducted various training sessions for developers on Git workflows and repository management.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'Laravel Framework: Building Robust Web Applications',
                'topics' => 'Laravel',
                'description' => 'Learn how to build scalable web applications using Laravel, the PHP framework known for its elegant syntax, powerful features, and developer-friendly tools.',
                'date' => '2025-04-03',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Tom Chua',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/6KzzUNInSXrFJidfeSSQsRlZagwaF0IB0TrpkNlC.jpg',
                'seminar_image' => 'images/seminar_images/mST8JkVqp7VLeiRAtpGEQynbcFG0mUbrZXPzgtA9.png',
                'about_the_speaker' => 'Tom Chua is a senior backend developer with extensive experience in Laravel and API development. He has worked on multiple enterprise-level applications and is a Laravel-certified trainer.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'MySQL Masterclass: Database Management and Optimization',
                'topics' => 'MySQL',
                'description' => 'This seminar covers MySQL database design, queries, indexing, security, and performance optimization techniques for efficient data management.',
                'date' => '2025-04-04',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Mark Angelo Hornido',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/EFq3cxLPCQ2nbOKEYTE4FI9Z1LyuZm8RKS3bFcdN.webp',
                'seminar_image' => 'images/seminar_images/1gguNhZUkGswGBxmWsYk8TsjElQTGGIxS2ke4DSl.png',
                'about_the_speaker' => 'Mark Angelo Hornido is a database administrator with over 10 years of experience in database design, SQL performance tuning, and cloud-based data solutions.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'PHP for Web Development: From Basics to Advanced',
                'topics' => 'PHP',
                'description' => 'This seminar provides a comprehensive guide to PHP, from syntax and functions to advanced topics like object-oriented programming and security best practices.',
                'date' => '2025-04-05',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Xyrus Abucal',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/3OS4rKoZp4lpxXUrxDsNpUzCEDIVFwHqarX00kkU.jpg',
                'seminar_image' => 'images/seminar_images/VmKcTrNmM9haSLtMOJmQZfu0RmfQOowI5rcKlJty.jpg',
                'about_the_speaker' => 'Xyrus Abucal is a full-stack web developer with expertise in PHP and Laravel. He has conducted multiple training sessions for aspiring PHP developers.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'React JS: Building Dynamic Frontend Applications',
                'topics' => 'React JS',
                'description' => 'Learn how to create interactive user interfaces with React JS, covering components, state management, hooks, and performance optimization.',
                'date' => '2025-04-06',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Lander Pelagio',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/JonuZX2HyObCnojfLlW3I6JUQ1htVYvhIGxOe6en.jpg',
                'seminar_image' => 'images/seminar_images/tUV6Lm3gy82vDotmV2elOyMmmrTpQfHUJJt1iEPa.png',
                'about_the_speaker' => 'Lander Pelagio is a front-end engineer specializing in React JS and modern JavaScript frameworks. He has led UI/UX development for various SaaS platforms.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'JavaScript Bootcamp: From Fundamentals to Advanced Concepts',
                'topics' => 'Javascript',
                'description' => 'This seminar dives deep into JavaScript, covering ES6+ features, asynchronous programming, event handling, and best practices for modern web development.',
                'date' => '2025-04-07',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Mark Angelo Hornido',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/4MAFl67IWB3F2zVLBvSl3Go6CmTGp8njLqTMnUw9.webp',
                'seminar_image' => 'images/seminar_images/j9VyuY4qrIEkZith0gcXr5w1jN0FfbmiFU51v6V3.jpg',
                'about_the_speaker' => 'Mark Angelo Hornido is a JavaScript enthusiast and educator who has been teaching JavaScript for over a decade. He has worked on several JavaScript-based projects and authored multiple tutorials.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'CSS for Modern Web Design: Styling with Confidence',
                'topics' => 'CSS',
                'description' => 'Explore CSS techniques, including Flexbox, Grid, animations, and responsive design, to create visually stunning and user-friendly websites.',
                'date' => '2025-04-08',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Jayson Delos Santos',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/GwCEkcnezNMCKf4dWcoeyIS9Las77njb0jl3TLou.jpg',
                'seminar_image' => 'images/seminar_images/BGV4FvxJRSdJLc6pN7lNL3aeNFb7H56TTB7AMyqr.jpg',
                'about_the_speaker' => 'Jayson Delos Santos is a UI/UX designer with a passion for front-end styling. He has worked with various startups and enterprises to create responsive and aesthetically pleasing web interfaces.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_of_seminar' => 'HTML for Beginners: The Foundation of Web Development',
                'topics' => 'HTML',
                'description' => 'Learn the fundamentals of HTML, including semantic tags, forms, accessibility, and best practices for structuring web pages.',
                'date' => '2025-04-09',
                'location' => 'DICT Training Center, Manila',
                'speaker_name' => 'Xyrus Abucal',
                'organization_name' => 'SupSoft Technologies',
                'speaker_image' => 'images/speaker_images/NnA2NxJlija23yVKT7Uuf5iaNt4VcTZhjGsY4qDr.jpg',
                'seminar_image' => 'images/seminar_images/sxRAfrYac43XtZufdPfA77HbCkJrpgVSjB4mhw71.jpg',
                'about_the_speaker' => 'Xyrus Abucal is a web development instructor with a strong background in HTML, CSS, and JavaScript. He has helped many beginners kickstart their careers in web development.',
                'certificate_template_id' => '1',
                'price' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'first_name' => "",
            'last_name' => "",
            'middle_name' => null,
            'age' => 0,
            'gender' => "",
            'address' => "",
            'country' => "",
            'province' => "",
            'bio' => "",
            'phone' => "",
            'password' => Hash::make('admin123'),
            'email_verified_at' => now()
        ]);

        UserProfile::create([
            'users_id' => 1,
            
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
