<?php
// Start session to store uploaded file info
session_start();

// Define upload directory
$upload_dir = "uploads/";

// Create directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Initialize message variable
$message = "";

// Initialize or retrieve carousel items from session
if (!isset($_SESSION["carousel_items"]) || !is_array($_SESSION["carousel_items"])) {
    $_SESSION["carousel_items"] = [];
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Handle adding new carousel item
    if (isset($_POST["add_carousel_item"])) {
        $new_item = [
            "title" => isset($_POST["carousel_title"]) ? $_POST["carousel_title"] : "",
            "description" => isset($_POST["carousel_description"]) ? $_POST["carousel_description"] : "",
            "image" => ""
        ];
        
        // Handle image upload
        if (isset($_FILES["carousel_image"]) && $_FILES["carousel_image"]["error"] == 0) {
            $file_name = basename($_FILES["carousel_image"]["name"]);
            $target_file = $upload_dir . "carousel_" . time() . "_" . $file_name;
            
            if (move_uploaded_file($_FILES["carousel_image"]["tmp_name"], $target_file)) {
                $new_item["image"] = $target_file;
                $message .= "Image uploaded successfully.<br>";
            } else {
                $message .= "Error uploading image.<br>";
            }
        }
        
        // Add new item to the carousel
        $_SESSION["carousel_items"][] = $new_item;
        $message .= "New carousel item added successfully.<br>";
    }
    
    // Handle removing carousel item
    if (isset($_POST["remove_item"]) && isset($_POST["item_index"])) {
        $index = (int)$_POST["item_index"];
        if (isset($_SESSION["carousel_items"][$index])) {
            // Delete the file if it exists
            if (!empty($_SESSION["carousel_items"][$index]["image"]) && file_exists($_SESSION["carousel_items"][$index]["image"])) {
                unlink($_SESSION["carousel_items"][$index]["image"]);
            }
            // Remove the item from the array
            array_splice($_SESSION["carousel_items"], $index, 1);
            $message .= "Carousel item removed successfully.<br>";
        }
    }
    
    // Handle editing carousel item
    if (isset($_POST["edit_item"]) && isset($_POST["edit_index"])) {
        $index = (int)$_POST["edit_index"];
        if (isset($_SESSION["carousel_items"][$index])) {
            if (isset($_POST["edit_title"])) {
                $_SESSION["carousel_items"][$index]["title"] = $_POST["edit_title"];
            }
            if (isset($_POST["edit_description"])) {
                $_SESSION["carousel_items"][$index]["description"] = $_POST["edit_description"];
            }
            
            // Handle image upload for editing
            if (isset($_FILES["edit_image"]) && $_FILES["edit_image"]["error"] == 0) {
                $file_name = basename($_FILES["edit_image"]["name"]);
                $target_file = $upload_dir . "carousel_" . time() . "_" . $file_name;
                
                if (move_uploaded_file($_FILES["edit_image"]["tmp_name"], $target_file)) {
                    // Delete the old image if it exists
                    if (!empty($_SESSION["carousel_items"][$index]["image"]) && file_exists($_SESSION["carousel_items"][$index]["image"])) {
                        unlink($_SESSION["carousel_items"][$index]["image"]);
                    }
                    $_SESSION["carousel_items"][$index]["image"] = $target_file;
                    $message .= "Image updated successfully.<br>";
                } else {
                    $message .= "Error updating image.<br>";
                }
            }
            
            $message .= "Carousel item updated successfully.<br>";
        }
    }
    
    // Handle photo banner 2 upload
    if (isset($_FILES["photo_banner_2"]) && $_FILES["photo_banner_2"]["error"] == 0) {
        $file_name = basename($_FILES["photo_banner_2"]["name"]);
        $target_file = $upload_dir . "photo_banner_2_" . time() . "_" . $file_name;
        
        if (move_uploaded_file($_FILES["photo_banner_2"]["tmp_name"], $target_file)) {
            $_SESSION["photo_banner_2"] = $target_file;
            $message .= "Photo Banner 2 uploaded successfully.<br>";
        } else {
            $message .= "Error uploading Photo Banner 2.<br>";
        }
    }
    
    // Handle text banner
    if (isset($_POST["text_banner"]) && !empty($_POST["text_banner"])) {
        $_SESSION["text_banner"] = $_POST["text_banner"];
        $message .= "Text Banner content saved successfully.<br>";
    }
    
    // Handle footer banner
    if (isset($_POST["footer_banner"]) && !empty($_POST["footer_banner"])) {
        $_SESSION["footer_banner"] = $_POST["footer_banner"];
        $message .= "Footer Banner content saved successfully.<br>";
    }
    
    if (empty($message)) {
        $message = "No changes were made.";
    } else {
        $message .= "<strong>All content has been updated in preview.php!</strong>";
    }
}

// Function to get item for editing
$edit_item = null;
$edit_index = -1;
if (isset($_GET["edit"]) && is_numeric($_GET["edit"])) {
    $edit_index = (int)$_GET["edit"];
    if (isset($_SESSION["carousel_items"][$edit_index])) {
        $edit_item = $_SESSION["carousel_items"][$edit_index];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .panel {
            background: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .file-input {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .button-red {
            background-color: #f44336;
        }
        .button-blue {
            background-color: #2196F3;
        }
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .preview-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .carousel-items {
            margin-top: 20px;
        }
        .carousel-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .carousel-item-info {
            flex: 1;
        }
        .carousel-item-actions {
            display: flex;
            gap: 5px;
        }
        .carousel-item-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 3px;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
        }
        .tab.active {
            background-color: white;
            border-bottom: 1px solid white;
            margin-bottom: -1px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
        <div class="notification">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="panel">
            <h2>Website Content Management</h2>
            
            <div class="tabs">
                <div class="tab active" data-tab="carousel">Carousel Management</div>
                <div class="tab" data-tab="other">Other Content</div>
            </div>
            
            <!-- Carousel Management Tab -->
            <div class="tab-content active" id="carousel-tab">
                <h3><?php echo $edit_item ? 'Edit Carousel Item' : 'Add New Carousel Item'; ?></h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <?php if ($edit_item): ?>
                        <input type="hidden" name="edit_index" value="<?php echo $edit_index; ?>">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="edit_title" value="<?php echo htmlspecialchars($edit_item['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="edit_description" required><?php echo htmlspecialchars($edit_item['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <?php if (!empty($edit_item['image'])): ?>
                                <div style="margin-bottom: 10px;">
                                    <img src="<?php echo htmlspecialchars($edit_item['image']); ?>" alt="Current image" style="max-width: 200px; max-height: 150px;">
                                    <p>Current image</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="edit_image" accept="image/*">
                            <p><small>Leave empty to keep current image</small></p>
                        </div>
                        <button type="submit" name="edit_item" class="button button-blue">Update Item</button>
                        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="button">Cancel</a>
                    <?php else: ?>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="carousel_title" placeholder="Slide Title" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="carousel_description" placeholder="Slide Description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="carousel_image" accept="image/*" required>
                        </div>
                        <button type="submit" name="add_carousel_item" class="button">Add to Carousel</button>
                    <?php endif; ?>
                </form>
                
                <div class="carousel-items">
                    <h3>Current Carousel Items</h3>
                    <?php if (empty($_SESSION["carousel_items"])): ?>
                        <p>No carousel items have been added yet.</p>
                    <?php else: ?>
                        <?php foreach ($_SESSION["carousel_items"] as $index => $item): ?>
                            <div class="carousel-item">
                                <?php if (!empty($item["image"])): ?>
                                    <img src="<?php echo htmlspecialchars($item["image"]); ?>" alt="<?php echo htmlspecialchars($item["title"]); ?>" class="carousel-item-image">
                                <?php else: ?>
                                    <div class="carousel-item-image" style="background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                                        <span>No Image</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="carousel-item-info">
                                    <strong><?php echo htmlspecialchars($item["title"]); ?></strong>
                                    <p><?php echo mb_substr(htmlspecialchars($item["description"]), 0, 50) . (mb_strlen($item["description"]) > 50 ? '...' : ''); ?></p>
                                </div>
                                
                                <div class="carousel-item-actions">
                                    <a href="?edit=<?php echo $index; ?>" class="button button-blue"><i class="fas fa-edit"></i></a>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline">
                                        <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                        <button type="submit" name="remove_item" class="button button-red" onclick="return confirm('Are you sure you want to remove this item?');"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Other Content Tab -->
            <div class="tab-content" id="other-tab">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>PHOTO BANNER 2 (Sidebar)</label>
                        <div class="file-input">
                            <input type="file" name="photo_banner_2" id="photo_banner_2">
                            <button type="submit" class="button">Upload</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>TEXT BANNER</label>
                        <textarea name="text_banner" id="text_banner" placeholder="Input text"><?php echo isset($_SESSION["text_banner"]) ? htmlspecialchars($_SESSION["text_banner"]) : ""; ?></textarea>
                        <button type="submit" class="button">Save</button>
                    </div>
                    
                    <div class="form-group">
                        <label>FOOTER BANNER</label>
                        <textarea name="footer_banner" id="footer_banner" placeholder="Input text"><?php echo isset($_SESSION["footer_banner"]) ? htmlspecialchars($_SESSION["footer_banner"]) : ""; ?></textarea>
                        <button type="submit" class="button">Save</button>
                    </div>
                </form>
            </div>
        </div>
        
        <a href="preview.php" class="preview-link button" target="_blank">View Layout Preview</a>
    </div>
    
    <script>
        // Simple tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding content
                const tabId = this.getAttribute('data-tab') + '-tab';
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>