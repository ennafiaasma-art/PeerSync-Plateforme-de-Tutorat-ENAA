<?php
session_start();

if (!isset($_SESSION['user'])) {
   __DIR__ ."/dashboard.php";
    exit;
}

if (($_SESSION['user']['role_name'] ?? '') !== 'student') {
    die('Access denied');
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#0F172A] text-[#F8FAFC] font-sans">
            <a href="../auth/logout.php"
               class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-red-500/20 font-medium">
                Logout
            </a>
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-3xl">
        <div class="rounded-3xl border border-[#475569]/30 bg-[#1E293B] p-8 shadow-2xl shadow-slate-950/40">
            <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                <div class="max-w-xl">
                    <span class="inline-flex items-center rounded-full border border-[#2563EB]/30 bg-[#2563EB]/10 px-4 py-1.5 text-sm font-semibold text-[#60A5FA]">
                        Student Dashboard
                    </span>
                    <h1 class="mt-5 text-3xl font-bold tracking-tight text-white md:text-4xl">
                        Welcome, <?= htmlspecialchars($_SESSION['user']['name']); ?>
                    </h1>
                    <p class="mt-3 text-sm leading-6 text-[#CBD5E1] md:text-base">
                        Enter a quiz access code, open the quiz, answer the questions, and see your score right away.
                    </p>
                </div>

                <div class="rounded-2xl border border-[#475569]/30 bg-[#0F172A]/70 p-5">
                    <p class="text-sm text-[#CBD5E1]">Ready to begin?</p>
                    <a href="enter-quiz-code.php" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-[#2563EB] to-[#1D4ED8] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 transition-all duration-300 hover:from-[#1D4ED8] hover:to-[#1E40AF] hover:scale-[1.02]">
                        Start Quiz
                    </a>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-[#475569]/30 bg-[#0F172A]/60 p-5">
                    <p class="text-sm text-[#CBD5E1]">Step 1</p>
                    <p class="mt-2 text-lg font-semibold text-white">Enter code</p>
                </div>
                <div class="rounded-2xl border border-[#475569]/30 bg-[#0F172A]/60 p-5">
                    <p class="text-sm text-[#CBD5E1]">Step 2</p>
                    <p class="mt-2 text-lg font-semibold text-white">Answer questions</p>
                </div>
                <div class="rounded-2xl border border-[#475569]/30 bg-[#0F172A]/60 p-5">
                    <p class="text-sm text-[#CBD5E1]">Step 3</p>
                    <p class="mt-2 text-lg font-semibold text-white">See your score</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>