<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Todo</title>
    </head>
    <body>
        <form method="POST" action="connect.php">
            <label for="title">Task Title:</label>
            <input type="text" name="title" id="title" required><br>

            <label for="completed">Completed:</label>
            <select name="completed" id="completed">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select><br>

            <button type="submit">Add Task</button><br>
        </form>
    </body>
</html>