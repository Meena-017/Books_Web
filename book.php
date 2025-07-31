<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: app.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=web;charset=utf8", "root", "Meena@2005");

// List of 15 popular books with sample stories
$books = [
    ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'story' => str_repeat("A gripping, heart-wrenching, and wholly remarkable tale of coming-of-age in a South poisoned by virulent prejudice. ", 50)],
    ['title' => '1984', 'author' => 'George Orwell', 'story' => str_repeat("A dystopian novel set in Airstrip One, a province of the Party under constant surveillance. ", 50)],
    ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'story' => str_repeat("A classic story of love and class distinctions in early 19th-century England. ", 50)],
    ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'story' => str_repeat("A tale of wealth, love, and the American Dream in 1920s New York. ", 50)],
    ['title' => 'Moby Dick', 'author' => 'Herman Melville', 'story' => str_repeat("The quest of Captain Ahab to exact revenge on the giant white whale, Moby Dick. ", 50)],
    ['title' => 'War and Peace', 'author' => 'Leo Tolstoy', 'story' => str_repeat("An epic tale of Russian society during the Napoleonic Wars. ", 50)],
    ['title' => 'The Odyssey', 'author' => 'Homer', 'story' => str_repeat("The journey of Odysseus as he strives to return home from the Trojan War. ", 50)],
    ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'story' => str_repeat("A young man’s journey through New York City and his struggles with growing up. ", 50)],
    ['title' => 'Jane Eyre', 'author' => 'Charlotte Brontë', 'story' => str_repeat("A gothic romance and the tale of an orphaned girl’s strength and resolve. ", 50)],
    ['title' => 'The Lord of the Rings', 'author' => 'J.R.R. Tolkien', 'story' => str_repeat("A fantasy epic about the quest to destroy the One Ring. ", 50)],
    ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'story' => str_repeat("The journey of Bilbo Baggins as he encounters dragons, dwarves, and adventure. ", 50)],
    ['title' => 'The Alchemist', 'author' => 'Paulo Coelho', 'story' => str_repeat("A spiritual journey of a shepherd boy seeking his Personal Legend. ", 50)],
    ['title' => 'Crime and Punishment', 'author' => 'Fyodor Dostoevsky', 'story' => str_repeat("A profound psychological analysis of a young man’s descent into crime. ", 50)],
    ['title' => 'The Grapes of Wrath', 'author' => 'John Steinbeck', 'story' => str_repeat("The journey of the Joad family as they flee the Dust Bowl for California. ", 50)],
    ['title' => 'Harry Potter and the Sorcerer\'s Stone', 'author' => 'J.K. Rowling', 'story' => str_repeat("The beginning of Harry Potter’s magical journey at Hogwarts School. ", 50)],
];

$elapsedTime = time() - $_SESSION['login_time'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
    <link rel="stylesheet" href="style.css">
    <script>
        let elapsedTime = <?php echo $elapsedTime; ?>;
        
        function startTimer() {
            const timerElement = document.getElementById('session-timer');
            setInterval(() => {
                elapsedTime++;
                let hours = Math.floor(elapsedTime / 3600);
                let minutes = Math.floor((elapsedTime % 3600) / 60);
                let seconds = elapsedTime % 60;
                timerElement.textContent = `${hours}h ${minutes}m ${seconds}s`;
            }, 1000);
        }

        function showStory(story) {
            document.getElementById('story-content').textContent = story;
            document.getElementById('story-modal').style.display = 'block';
        }

        function closeStory() {
            document.getElementById('story-modal').style.display = 'none';
        }

        window.onload = startTimer;
    </script>
</head>
<body>
    <div class="navbar">
        <span>Welcome, User | Session Duration: <span id="session-timer"></span></span>
        <a href="users.php?logout=true" class="logout-button">Logout</a>
    </div>

    <h1>Available Books</h1>
    <div class="book-list">
        <?php foreach ($books as $book): ?>
            <div class="book">
                <h2><?php echo htmlspecialchars($book['title']); ?></h2>
                <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                <button onclick="showStory('<?php echo addslashes($book['story']); ?>')" class="read-button">Read</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal for Story Reading -->
    <div id="story-modal" class="modal">
        <div class="modal-content">
            <span onclick="closeStory()" class="close-button">&times;</span>
            <h2>Story</h2>
            <p id="story-content"></p>
        </div>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        background-color: #007bff;
        padding: 10px;
        color: white;
    }

    .logout-button {
        color: white;
        text-decoration: none;
    }

    h1 {
        color: #333;
    }

    .book-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .book {
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        width: 250px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .read-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }

    .read-button:hover {
        background-color: #0056b3;
    }

   /* Modal styling */
.modal {
    display: none; /* Hidden by default, will be shown when triggered */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8); /* Dark background */
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

/* Modal content (story display) */
.modal-content {
    background: white;
    padding: 20px;
    width: 80%; /* Adjust to fit your preference */
    max-width: 800px;
    max-height: 80%;
    overflow-y: auto;
    border-radius: 5px;
    position: relative;
    text-align: center;
}

/* Close button style */
.close-button {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
}
</style>
