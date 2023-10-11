<!DOCTYPE html>
<html>
<head>
    <title>Book Shop</title>
</head>
<body>
    <h1>Book Shop</h1>

    <!-- Form for adding a new book -->
    <h2> To add any new book </h2>
    <form method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="author">Author:</label>
        <input type="text" name="author" required>

        <label for="available">Available:</label>
        <select name="available">
            <option value="YES">YES</option>
            <option value="NO">NO</option>
        </select>

        <label for="pages">Pages:</label>
        <input type="number" name="pages" required>
        <label for="isbn">ISBN:</label>

        <input type="text" name="isbn" required>

        <input type="submit" name="add" value="To add Book Item"><br>
    </form>

    <?php
    $jsonFile = 'books.json';

    // Function to read data from the JSON file
    function readBooks() {
        global $jsonFile;
        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            return json_decode($jsonContent, true);
        }
        return [];
    }

    // Function to save data to the JSON file
    function saveBooks($data) {
        global $jsonFile;
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($jsonFile, $jsonContent);
    }

    // Function to add a new book
    if (isset($_POST['add'])) {
        $books = readBooks();
        $newBook = [
            'title' => $_POST['title'],
            'author' => $_POST['author'],
            'available' => $_POST['available'],
            'pages' => $_POST['pages'],
            'isbn' => $_POST['isbn']
        ];
        $books[] = $newBook;
        saveBooks($books);
    }

    // Function to delete a book by ISBN
    if (isset($_POST['delete'])) {
        $isbnToDelete = $_POST['delete'];
        $books = readBooks();
        foreach ($books as $key => $book) {
            if ($book['isbn'] == $isbnToDelete) {
                unset($books[$key]);
                break;
            }
        }
        saveBooks($books);
    }

    // Function to search books by title or author
    // echo '<h1> To Search any Books  by title or author </h1>';
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        $books = readBooks();
        $results = [];
        foreach ($books as $book) {
            if (stripos($book['title'], $searchTerm) !== false || stripos($book['author'], $searchTerm) !== false) {
                $results[] = $book;
            }
        }
    } else {
        $results = readBooks();
    }
    echo '<br>';
    ?>

    <!-- <h1> To Search any Books </h1> -->
    <form method="post">
        <label for="search">Search by Title/Author:</label>
        <input type="text" name="search">
        <input type="submit" value="Search">
    </form>

    <h2> The all  books detailos below in HTML table </h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Available</th>
            <th>Pages</th>
            <th>ISBN</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($results as $book) {
            echo '<tr>';
            echo '<td>' . $book['title'] . '</td>';
            echo '<td>' . $book['author'] . '</td>';
            echo '<td>' . $book['available'] . '</td>';
            echo '<td>' . $book['pages'] . '</td>';
            echo '<td>' . $book['isbn'] . '</td>';
            echo '<td>
                    <form method="post">
                        <input type="hidden" name="delete" value="' . $book['isbn'] . '">
                        <input type="submit" value="Delete">
                    </form>
                </td>';
            echo '</tr>';
        }
        ?>
    </table>
</body>
</html>


