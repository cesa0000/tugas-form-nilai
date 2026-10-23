<?php
// Inisialisasi variabel
$nama = $email = $nilai = "";
$errors = [];
$hasil = null;

// Form processing: jalankan hanya saat form dikirim (method POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama  = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $nilai = trim($_POST["nilai"] ?? "");

    // Validasi input
    if ($nama === "") {
        $errors[] = "Nama wajib diisi.";
    }

    if ($email === "") {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if ($nilai === "") {
        $errors[] = "Nilai ujian wajib diisi.";
    } elseif (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
        $errors[] = "Nilai ujian harus berupa angka antara 0 sampai 100.";
    }

    // Struktur kendali (percabangan)
    if (empty($errors)) {
        $nilai = (float) $nilai;

        if ($nilai > 70) {
            $hasil = "Lulus";
        } else {
            // nilai < 70 => Remedial (nilai tepat 70 juga Remedial karena tidak > 70)
            $hasil = "Remedial";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Nilai Ujian</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 40px 16px;
        }
        .card {
            max-width: 440px;
            margin: 0 auto;
            background: #fff;
            padding: 28px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }
        h1 { margin-top: 0; font-size: 22px; }
        label { display: block; margin: 14px 0 6px; font-weight: bold; }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 11px;
            background: #2563eb;
            color: #fff;
            border: 0;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover { background: #1d4ed8; }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        .error ul { margin: 0; padding-left: 18px; }
        .hasil {
            margin-top: 22px;
            padding: 16px;
            border-radius: 8px;
        }
        .lulus    { background: #dcfce7; color: #166534; }
        .remedial { background: #fef3c7; color: #92400e; }
        .hasil h2 { margin: 0 0 10px; }
        .hasil p  { margin: 4px 0; }
    </style>
</head>
<body>
<div class="card">
    <h1>Form Nilai Ujian</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="nilai">Nilai Ujian</label>
        <input type="number" id="nilai" name="nilai" min="0" max="100" step="any"
               value="<?= htmlspecialchars((string) $nilai) ?>" required>

        <button type="submit">Kirim</button>
    </form>

    <?php if ($hasil !== null): ?>
        <div class="hasil <?= strtolower($hasil) ?>">
            <h2>Hasil</h2>
            <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Nilai Ujian:</strong> <?= htmlspecialchars((string) $nilai) ?></p>
            <p><strong>Status:</strong> <?= $hasil ?></p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
