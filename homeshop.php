<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veggen - Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
    <link rel="stylesheet" href="main.css">
</head>
<body>
<section class="popular-brands">
    <h2>Popular Brands</h2>
    <div class="control">
        <i class="bi bi-chevron-left left"></i>
        <i class="bi bi-chevron-right right"></i>
    </div>
    <div class="popular-brands-content">
        <?php
        include("config.php");
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <div class="card">
                    <div class="price">Price: $<?php echo htmlspecialchars($fetch_products['price']); ?></div>
                    <div class="name">Name: <?php echo htmlspecialchars($fetch_products['name']); ?></div>
                    <div class="icon">
                        <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="bi bi-eye-fill"></a>
                        <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                        <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No products found!</p>";
        }
        ?>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
$(document).ready(function() {
    $('.popular-brands-content').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: $('.bi-chevron-left'),
        nextArrow: $('.bi-chevron-right'),
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            { breakpoint: 1024, settings: { slidesToShow: 3 } },
            { breakpoint: 768, settings: { slidesToShow: 2 } },
            { breakpoint: 480, settings: { slidesToShow: 1 } }
        ]
    });
    $('button[name="add_to_wishlist"]').on('click', function(e) {
        e.preventDefault();
        alert('Added to wishlist!');
    });
    $('button[name="add_to_cart"]').on('click', function(e) {
        e.preventDefault();
        alert('Added to cart!');
    });
});
</script>
</body>
</html>