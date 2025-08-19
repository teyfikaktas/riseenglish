<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelime Blast</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            margin: 0; 
            padding: 0; 
            overflow: hidden; 
            background: #1e1b4b;
        }
        #game-container {
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div id="game-container"></div>
    
    <script>
        // Sayfa yüklendiğinde oyunu başlat
        document.addEventListener('DOMContentLoaded', function() {
            const game = new Phaser.Game({
                ...window.GameConfig,
                parent: 'game-container'
            });
        });
    </script>
</body>
</html>