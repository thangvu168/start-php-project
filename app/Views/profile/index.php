<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>

<body>
    <h1>Profile</h1>
    <form action="/profile" method="post" enctype="multipart/form-data">
        <p>Avatar:</p>
        <img src="<?= $user['avatar'] ?>" id="avatarPreview" />
        <input type="file" name="avatar" accept="image/*" id="avatarInput">
        <p>Username: </p>
        <input type="text" value="<?= $user['username'] ?>" disabled>
        <p>Email: </p>
        <input type="email" value="<?= $user['email'] ?>" disabled>
        <p>First name: </p>
        <input type="text" value="<?= $user['first_name'] ?>" name="first_name">
        <p>Last name: </p>
        <input type="text" value="<?= $user['last_name'] ?>" name="last_name">

        <button type="submit">Submit</button>
    </form>

</body>

</html>

<script>
    document.getElementById('avatarInput').addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (!file) return;

        // chỉ cho image
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            return;
        }

        // cách 1 (hiện đại, nhanh nhất)
        const imageUrl = URL.createObjectURL(file);

        document.getElementById('avatarPreview').src = imageUrl;
    });
</script>