<header>
        <nav>
            <div class="logo-dropdown">
                <a href="index.php"><img src="images/logo.jpeg" alt="Pets Victoria Logo"></a>
                <select onchange="navigate(this.value)">
                    <option value="" disabled selected>Select an option</option>
                    <option value="index.php">Home</option>
                    <option value="pets.php">Pets</option>
                    <option value="add.php">Add</option>
                    <option value="gallery.php">Gallery</option>
                </select>
            </div>
            <form>
                <input type="text" placeholder="Search">
                <span class="material-icons">search</span>
            </form>
        </nav>
</header>