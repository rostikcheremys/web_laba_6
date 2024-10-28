<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Погода</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Погода</h1>
    </header>

    <main>
        <?php $weatherData = require 'weather.php'; ?>

        <p class="weatherData">Дата: <?= $weatherData['date'] ?></p>
        <p class="weatherData">Погода: <?= $weatherData['city'] ?></p>
        <p class="weatherData">Час сходу: <?= $weatherData['sunrise'] ?></p>
        <p class="weatherData">Час заходу: <?= $weatherData['sunset'] ?></p>
        <p class="weatherData">Тривалість дня: <?= $weatherData['day_length'] ?></p>
        <p class="weatherData">Температура протягом дня: <?= $weatherData['temperature'] ?></p>
    </main>

    <footer>
        <p>made by Rostyslav Cheremys, 2024</p>
    </footer>
</body>
</html>