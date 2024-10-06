<?php
$title = "Pets Victoria - Add a Pet";
include('includes/header.inc');
include('includes/nav.inc');
?>

    <main>
        <h2>Add a pet</h2>
        <p class="sub-title">You can add a new pet here</p>
        <div class="content">
            <form action="process_add_pet.php" class="add-pet-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="pet-name">Pet name: <span>*</span></label>
                    <input type="text" id="pet-name" name="petname" placeholder="Provide a name for the pet" class="full-width">
                </div>
                <div class="form-group">
                    <label for="pet-type-select">Type: <span>*</span></label>
                    <select name="type" id="pet-type-select">
                        <option value="" disabled selected>--Choose an option--</option>
                        <option value="cat">Cat</option>
                        <option value="dog">Dog</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description: <span>*</span></label>
                    <textarea id="description" name="description" placeholder="Describe the pet briefly" required></textarea>
                </div>
                <div class="form-group choose-file">
                    <label for="image">Select an image: <span>*</span></label>
                    <input type="file" id="image" name="file01" required>
                    <span class="max-size"><i>Max image size 500 px</i></span>
                </div>
                <div class="form-group">
                    <label for="image-caption">Image caption: <span>*</span></label>
                    <input type="text" id="image-caption" name="caption" placeholder="Describe the image in one word" class="full-width" required>
                </div>
                <div class="form-group">
                    <label for="age">Age (months): <span>*</span></label>
                    <input type="text" id="age" name="age" placeholder="Age of the pet in months" class="full-width" required>
                </div>
                <div class="form-group">
                    <label for="location">Location: <span>*</span></label>
                    <input type="text" id="location" name="location" placeholder="Location of the pet" class="full-width" required>
                </div>
                <div class="button-group">
                    <button class="submit-button">
                        <span class="material-icons">add_task</span>
                        submit
                    </button>
                    <button type="reset" class="clear-button">
                        <span class="material-icons">close</span>
                        clear
                    </button>
                </div>
            </form>

        </div>
    </main>
<?php
include('includes/footer.inc');
?>