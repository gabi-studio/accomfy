<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/session.php';

$userModel = new User($pdo);

// REGISTER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Optional: Check for duplicate email
    $existing = $userModel->findByEmail($_POST['email']);
    if ($existing) {
        header('Location: /accomfy/views/auth/register.php?error=exists');
        exit();
    }

    $data = [
        'first_name'            => $_POST['first_name'],
        'last_name'             => $_POST['last_name'],
        'email'                 => $_POST['email'],
        'password_hash'         => hash('sha256', $_POST['password']),
        'phone_number'          => $_POST['phone'],
        'role'                  => $_POST['role'], // student or landlord
        'school'                => $_POST['school'] ?? null,
        'program'               => $_POST['program'] ?? null,
        'profile_photo'         => '', // Handle uploads later
        'about'                 => $_POST['about'] ?? '',
        'preferred_location'    => $_POST['preferred_location'] ?? '',
        'preferred_furnishing'  => $_POST['preferred_furnishing'] ?? '',
        'roommate_style'        => $_POST['roommate_style'] ?? ''

    ];

    if ($userModel->register($data)) {
        header('Location: /accomfy/views/auth/login.php?success=registered');
        exit();
    } else {
        header('Location: /accomfy/views/auth/register.php?error=failed');
        exit();
    }
}

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userModel->findByEmail($email);
    if ($user && hash('sha256', $password) === $user['password_hash']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: /accomfy/views/dashboard/index.php');
        } elseif ($user['role'] === 'landlord') {
            header('Location: /accomfy/views/dashboard/my-listings.php');
        } else {
            header('Location: /accomfy/views/dashboard/saved-listings.php');
        }
        exit();
    } else {
        header('Location: /accomfy/views/auth/login.php?error=invalid');
        exit();
    }
}
